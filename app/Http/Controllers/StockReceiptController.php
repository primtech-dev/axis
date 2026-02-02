<?php

namespace App\Http\Controllers;

use App\Models\StockReceipt;
use App\Models\StockReceiptItem;
use App\Models\Inventory;
use App\Models\StockMovement;
use App\Models\ProductVariant;
use App\Models\Branch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class StockReceiptController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = StockReceipt::with('branch','creator')->select(['id','public_id','branch_id','supplier_name','reference_no','status','total_items','created_at','received_at']);
            // basic datatable server side (similar to categories example)
            return datatables()->eloquent($query)
                ->addIndexColumn()
                ->addColumn('branch', fn($r) => $r->branch->name ?? '-')
                ->addColumn('created_at', fn($r) => $r->created_at?->format('d M Y H:i') ?? '-')
                ->addColumn('action', function($r){
                    return view('stock_receipts._column_action',['r'=>$r])->render();
                })
                ->rawColumns(['action'])
                ->toJson();
        }

        return view('stock_receipts.index');
    }

    public function create()
    {
        $branches = \App\Models\Branch::orderBy('name')->get();
        return view('stock_receipts.create', ['receipt' => new StockReceipt(), 'branches' => $branches]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'branch_id' => 'required|integer|exists:branches,id',
            'supplier_name' => 'nullable|string|max:255',
            'reference_no' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.variant_id' => 'required|integer|exists:product_variants,id',
            'items.*.quantity_received' => 'required|integer|min:1',
            'status' => ['required', Rule::in(['draft','received','cancelled'])]
        ]);

        $userId = auth()->user()->id ?? null;

        DB::beginTransaction();
        try {
            $receipt = StockReceipt::create([
                'branch_id' => $validated['branch_id'],
                'supplier_name' => $validated['supplier_name'] ?? null,
                'reference_no' => $validated['reference_no'] ?? null,
                'notes' => $validated['notes'] ?? null,
                'status' => $validated['status'],
                'total_items' => count($validated['items']),
                'created_by' => $userId,
                'received_at' => $validated['status'] === 'received' ? Carbon::now() : null,
            ]);

            // loop items: simpan StockReceiptItem dan update inventory jika status == received
            foreach ($validated['items'] as $it) {
                $item = StockReceiptItem::create([
                    'receipt_id' => $receipt->id,
                    'variant_id' => $it['variant_id'],
                    'quantity_received' => $it['quantity_received']
                ]);

                if ($receipt->status === 'received') {
                    // apply to inventory (atomic)
                    $this->applyIncomingToInventory($receipt->branch_id, $it['variant_id'], $it['quantity_received'], $userId, $receipt);
                } else {
                    // draft or cancelled: do not touch inventory since `incoming` column removed
                    // Optionally we could record planned qty in receipt.meta — skip by default.
                }
            }

            DB::commit();
            return redirect()->route('stock_receipts.show', $receipt->id)->with('success', 'Stok masuk berhasil dibuat.');
        } catch (\Throwable $th) {
            Log::error('StockReceipt.store ERROR: '.$th->getMessage(), ['exception' => $th]);
            DB::rollBack();
            return back()->withInput()->with('error', 'Gagal menyimpan stok masuk: '.$th->getMessage());
        }
    }

    protected function increaseIncomingCount(int $branchId, int $variantId, int $qty)
    {
        $inv = Inventory::firstOrCreate(
            ['variant_id' => $variantId, 'branch_id' => $branchId],
            ['on_hand' => 0, 'available' => 0, 'reserved' => 0]
        );
//        $inv->incoming = ($inv->incoming ?? 0) + $qty;
        // optionally recalc available = on_hand - reserved
        $inv->save();
    }

    protected function applyIncomingToInventory(int $branchId, int $variantId, int $qty, $userId = null, StockReceipt $receipt = null)
    {
        // Pastikan pemanggilan berada dalam transaksi DB
        // Ambil baris inventory dengan lock
        $invRow = DB::table('inventory')
            ->where('variant_id', $variantId)
            ->where('branch_id', $branchId)
            ->lockForUpdate()
            ->first();

        if ($invRow) {
            // update existing row
            $newOnHand = intval($invRow->on_hand) + intval($qty);
            $reserved = intval($invRow->reserved ?? 0);
            $newAvailable = max(0, $newOnHand - $reserved);

            DB::table('inventory')
                ->where('id', $invRow->id)
                ->update([
                    'on_hand' => $newOnHand,
                    'available' => $newAvailable,
                    'updated_at' => now(),
                ]);

            // refresh $inventory model
            $inventory = Inventory::find($invRow->id);
        } else {
            // create new inventory row
            $inventory = Inventory::create([
                'variant_id' => $variantId,
                'branch_id' => $branchId,
                'on_hand' => $qty,
                'available' => max(0, $qty - 0), // reserved = 0
                'reserved' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // create stock movement record
        $movement = StockMovement::create([
            'variant_id' => $variantId,
            'branch_id' => $branchId,
            'change' => $qty,
            'resulting_on_hand' => $inventory->on_hand,
            'reason' => 'receipt',
            'reference_type' => 'receipt',
            'reference_id' => $receipt ? $receipt->public_id : null,
            'performed_by' => $userId,
            'metadata' => [
                'note' => 'Stock received via StockReceipt id=' . ($receipt ? $receipt->id : 'unknown'),
            ],
            'created_at' => now(),
        ]);

        return $movement;
    }

    public function show($id)
    {
        $receipt = StockReceipt::with(['items.variant.product','branch','creator'])->findOrFail($id);
        return view('stock_receipts.show', compact('receipt'));
    }

    // optional helper search for variant autocomplete
    public function variantSearch(Request $request)
    {
        $q = trim($request->input('q', ''));
        $branchId = $request->input('branch_id');

        // pastikan relasi product() ada di ProductVariant
        $query = \App\Models\ProductVariant::with('product')->select(['id','sku','variant_name','product_id']);

        if ($q !== '') {
            $query->where(function($sub) use ($q) {
                // search in sku or variant_name or product.name (via relation)
                $sub->where('sku', 'ilike', "%{$q}%")
                    ->orWhere('variant_name', 'ilike', "%{$q}%")
                    ->orWhereHas('product', function($p) use ($q) {
                        $p->where('name', 'ilike', "%{$q}%");
                    });
            });
        }

        // Optional: filter by branch stock availability (uncomment if you want)
        // if ($branchId) {
        //     $query->whereHas('inventories', function($iq) use ($branchId) {
        //         $iq->where('branch_id', $branchId);
        //     });
        // }

        $variants = $query->limit(20)->get();

        $results = $variants->map(function($v) {
            $labelName = $v->variant_name ?? ($v->product->name ?? null);

            return [
                'id' => $v->id,
                'text' => trim($v->sku . ($labelName ? ' — ' . $labelName : '')),
                'sku' => $v->sku,
                'variant_name' => $labelName,
            ];
        });

        return response()->json(['results' => $results]);
    }

    public function edit(int $id)
    {
        $receipt = StockReceipt::with('items.variant.product')->findOrFail($id);
        $branches = Branch::orderBy('name')->get();
        // reuse create view but pass $receipt
        return view('stock_receipts.edit', compact('receipt','branches'));
    }

    public function update(Request $request, int $id)
    {
        $validated = $request->validate([
            'branch_id' => 'required|integer|exists:branches,id',
            'supplier_name' => 'nullable|string|max:255',
            'reference_no' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.variant_id' => 'required|integer|exists:product_variants,id',
            'items.*.quantity_received' => 'required|integer|min:1',
            'status' => ['required', Rule::in(['draft','received','cancelled'])]
        ]);

        $userId = auth()->id();
        $receipt = StockReceipt::with('items')->findOrFail($id);

        DB::beginTransaction();
        try {
            $originalStatus = $receipt->status;
            $newStatus = $validated['status'];

            // Update receipt core fields
            $receipt->update([
                'branch_id' => $validated['branch_id'],
                'supplier_name' => $validated['supplier_name'] ?? null,
                'reference_no' => $validated['reference_no'] ?? null,
                'notes' => $validated['notes'] ?? null,
                'status' => $newStatus,
                'total_items' => count($validated['items']),
                'received_at' => ($newStatus === 'received') ? ($receipt->received_at ?? Carbon::now()) : null,
            ]);

            // Build associative arrays of old items keyed by variant_id
            $oldItems = [];
            foreach ($receipt->items as $it) {
                $oldItems[$it->variant_id] = $it;
            }

            // Build new items keyed by variant_id
            $newItems = [];
            foreach ($validated['items'] as $it) {
                $vid = intval($it['variant_id']);
                $qty = intval($it['quantity_received']);
                if (isset($newItems[$vid])) {
                    // If duplicates in input, sum them
                    $newItems[$vid] += $qty;
                } else {
                    $newItems[$vid] = $qty;
                }
            }

            // If original was received and new is not received -> reverse whole original receipt
            if ($originalStatus === 'received' && $newStatus !== 'received') {
                // Reverse all old items (reduce inventory)
                foreach ($oldItems as $variantId => $oldItem) {
                    $this->reverseSingleInventoryChange($receipt->branch_id, $variantId, $oldItem->quantity_received, $userId, $receipt, 'receipt_reverse_on_update');
                }
            }

            // If original was not received and new is received -> apply all new items
            if ($originalStatus !== 'received' && $newStatus === 'received') {
                foreach ($newItems as $variantId => $qty) {
                    $this->applyIncomingToInventory($validated['branch_id'], $variantId, $qty, $userId, $receipt);
                }
            }

            // If both are received -> compute diffs per variant and apply changes (increase or decrease)
            if ($originalStatus === 'received' && $newStatus === 'received') {
                // variants present either in old or new
                $allVariantIds = array_unique(array_merge(array_keys($oldItems), array_keys($newItems)));
                foreach ($allVariantIds as $variantId) {
                    $oldQty = isset($oldItems[$variantId]) ? intval($oldItems[$variantId]->quantity_received) : 0;
                    $newQty = isset($newItems[$variantId]) ? intval($newItems[$variantId]) : 0;
                    $diff = $newQty - $oldQty;
                    if ($diff === 0) continue;
                    if ($diff > 0) {
                        // increase inventory by diff
                        $this->applyIncomingToInventory($validated['branch_id'], $variantId, $diff, $userId, $receipt);
                    } else {
                        // decrease inventory by -diff (reverse)
                        $this->reverseSingleInventoryChange($validated['branch_id'], $variantId, abs($diff), $userId, $receipt, 'receipt_adjustment');
                    }
                }
            }

            // After handling inventory diffs, sync StockReceiptItem rows:
            // Simplest: delete all old items and recreate from validated input (transaction safe).
            // If you need to preserve item row IDs, change to update/create logic; here we recreate.
            StockReceiptItem::where('receipt_id', $receipt->id)->delete();
            foreach ($newItems as $variantId => $qty) {
                StockReceiptItem::create([
                    'receipt_id' => $receipt->id,
                    'variant_id' => $variantId,
                    'quantity_received' => $qty
                ]);
            }

            DB::commit();
            return redirect()->route('stock_receipts.show', $receipt->id)->with('success', 'Stock receipt berhasil diperbarui.');
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error('StockReceipt.update ERROR: '.$th->getMessage(), ['exception' => $th]);
            return back()->withInput()->with('error', 'Gagal memperbarui stok masuk: '.$th->getMessage());
        }
    }

    protected function reverseSingleInventoryChange(int $branchId, int $variantId, int $qty, $userId = null, StockReceipt $receipt = null, string $reason = 'receipt_reverse')
    {
        // lock row
        $invRow = DB::table('inventory')
            ->where('variant_id', $variantId)
            ->where('branch_id', $branchId)
            ->lockForUpdate()
            ->first();

        if (!$invRow) {
            throw new \Exception("Inventory row not found for variant {$variantId} at branch {$branchId} when reversing.");
        }

        $currentOnHand = intval($invRow->on_hand);
        if ($currentOnHand < $qty) {
            throw new \Exception("Cannot reverse {$qty} units for variant {$variantId} at branch {$branchId} — on_hand ({$currentOnHand}) too low.");
        }

        $newOnHand = $currentOnHand - $qty;
        $reserved = intval($invRow->reserved ?? 0);
        $newAvailable = max(0, $newOnHand - $reserved);

        DB::table('inventory')
            ->where('id', $invRow->id)
            ->update([
                'on_hand' => $newOnHand,
                'available' => $newAvailable,
                'updated_at' => now(),
            ]);

        $inventory = Inventory::find($invRow->id);

        // record stock movement (negative change)
        StockMovement::create([
            'variant_id' => $variantId,
            'branch_id' => $branchId,
            'change' => -1 * $qty,
            'resulting_on_hand' => $inventory->on_hand,
            'reason' => $reason,
            'reference_type' => 'receipt',
            'reference_id' => $receipt ? $receipt->public_id : null,
            'performed_by' => $userId,
            'metadata' => [
                'note' => "Reversal for StockReceipt id=" . ($receipt ? $receipt->id : 'unknown')
            ],
            'created_at' => now(),
        ]);

        return true;
    }

    public function destroy(int $id)
    {
        $receipt = StockReceipt::with('items')->findOrFail($id);
        $userId = auth()->id();

        DB::beginTransaction();
        try {
            if ($receipt->status === 'received') {
                // reverse all items
                foreach ($receipt->items as $it) {
                    $this->reverseSingleInventoryChange($receipt->branch_id, $it->variant_id, $it->quantity_received, $userId, $receipt, 'receipt_deleted');
                }
            }
            // then delete items + receipt
            StockReceiptItem::where('receipt_id', $receipt->id)->delete();
            $receipt->delete();

            DB::commit();
            return redirect()->route('stock_receipts.index')->with('success', 'Stock receipt berhasil dihapus.');
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error('StockReceipt.destroy ERROR: '.$th->getMessage(), ['exception' => $th]);
            return back()->with('error', 'Gagal menghapus stock receipt: ' . $th->getMessage());
        }
    }
}
