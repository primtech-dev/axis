<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use App\Services\InventoryService;

class OrderController extends Controller
{
    private const VALIDATION_MESSAGES = [
        'status.in' => 'Status tidak valid',
    ];

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = Order::select([
                'id','public_id','order_no','customer_id','branch_id','status',
                'total_amount','payment_status','created_at'
            ])
                ->with(['customer:id,full_name','branch:id,name']);

            // jika admin cabang: batasi ke branch mereka (contoh: middleware/logic lain bisa set user->branch_id)
            if ($request->user()->hasRole('branch_admin')) {
                if ($request->user()->branch_id) {
                    $query->where('branch_id', $request->user()->branch_id);
                }
            }

            // search
            $searchValue = $request->input('search.value');
            if (!empty($searchValue)) {
                $query->where(function ($q) use ($searchValue) {
                    $q->where('order_no', 'ilike', "%{$searchValue}%")
                        ->orWhere('public_id', 'ilike', "%{$searchValue}%")
                        ->orWhereHas('customer', function($qc) use ($searchValue) {
                            $qc->where('full_name', 'ilike', "%{$searchValue}%");
                        });
                });
            }

            // filter status/branch if needed
            if ($request->filled('status')) {
                $query->where('status', $request->input('status'));
            }
            if ($request->filled('branch_id')) {
                $query->where('branch_id', $request->input('branch_id'));
            }

            return datatables()->eloquent($query)
                ->addIndexColumn()

                // RELATION (non-sortable)
                ->addColumn('customer', function (Order $o) {
                    return $o->customer ? e($o->customer->full_name) : '-';
                })
                ->addColumn('branch', function (Order $o) {
                    return $o->branch ? e($o->branch->name) : '-';
                })

                // SORTABLE DB COLUMNS
                ->editColumn('total_amount', function (Order $o) {
                    return number_format($o->total_amount, 0, ',', '.');
                })
                ->orderColumn('total_amount', function ($q, $order) {
                    $q->orderBy('orders.total_amount', $order);
                })

                ->editColumn('status', function (Order $o) {
                    return ucfirst($o->status);
                })
                ->orderColumn('status', function ($q, $order) {
                    $q->orderBy('orders.status', $order);
                })

                ->editColumn('created_at', function (Order $o) {
                    return $o->created_at ? $o->created_at->format('d M Y H:i') : '-';
                })
                ->orderColumn('created_at', function ($q, $order) {
                    $q->orderBy('orders.created_at', $order);
                })

                // ACTION
                ->addColumn('action', function (Order $o) {
                    return view('orders._column_action', ['order' => $o])->render();
                })

                ->rawColumns(['action'])
                ->toJson();
        }

        return view('orders.index');
    }

    public function show(int $id)
    {
        $order = Order::with(['items.variant','items.product','customer','branch','paymentMethod','address'])->findOrFail($id);
        return view('orders.show', compact('order'));
    }

    /**
     * Update status (e.g., pending -> processing -> shipped -> complete / cancelled)
     */
    public function updateStatus(Request $request, int $id)
    {
        $order = Order::findOrFail($id);

        $validated = $request->validate([
            'status' => ['required', Rule::in(['pending','paid','processing','shipped','complete','cancelled','return'])],
            'notes' => 'nullable|string|max:1000'
        ], self::VALIDATION_MESSAGES);

        DB::beginTransaction();
        try {
            $oldStatus = $order->status;
            $order->status = $validated['status'];
            if (isset($validated['notes'])) {
                $order->notes = trim($order->notes . "\n\n" . now()->format('Y-m-d H:i') . ' - ' . $request->user()->name . ': ' . $validated['notes']);
            }

            // di dalam controller method
            $inventoryService = app()->make(InventoryService::class);

            if ($validated['status'] === 'cancelled') {
                $order->cancelled_at = now();
                // unreserve stock
                $ok = $inventoryService->unreserveOrder($order, $request->user()->id ?? null);
                if (!$ok) {
                    throw new \Exception('Gagal melakukan unreserve stok. Cek log.');
                }
            }

            if ($validated['status'] === 'shipped') {
                // konfirmasi shipment -> kurangi on_hand & reserved
                $ok = $inventoryService->confirmShipment($order, $request->user()->id ?? null);
                if (!$ok) {
                    throw new \Exception('Gagal konfirmasi pengiriman stok. Cek log.');
                }
            }

            $order->save();

            DB::commit();
            return redirect()->route('admin.orders.show', $order->id)->with('success', 'Status order berhasil diperbarui');
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', $th->getMessage());
        }
    }

    public function destroy(int $id)
    {
        $order = Order::findOrFail($id);

        try {
            $order->delete(); // soft delete
            return redirect()->route('admin.orders.index')->with('success', 'Order berhasil dihapus');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }
    }

    public function print(int $id)
    {
        $order = Order::with([
            'items.product',
            'items.variant',
            'customer',
            'branch',
            'address'
        ])->findOrFail($id);

        // view khusus print (tanpa layout sidebar)
        return view('orders.print', compact('order'));
    }
}
