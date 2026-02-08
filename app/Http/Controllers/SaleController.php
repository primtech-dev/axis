<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sale;
use App\Models\Customer;
use App\Models\Product;
use App\Models\PaymentMethod;
use App\Models\SaleItem;
use App\Models\StockMovement;
use App\Models\Payment;
use App\Models\Category;
use App\Models\Purchase;
use App\Models\Account;
use App\Models\Journal;
use App\Models\JournalDetail;
use App\Models\PurchaseItem;
use App\Models\CustomerReceivable;
use Illuminate\Support\Facades\DB;
use App\Services\SaleInvoiceService;

class SaleController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {

            $query = Sale::with('customer')
                ->select('sales.*');

            return datatables()->eloquent($query)
                ->addIndexColumn()
                ->addColumn('date', function ($s) {
                    return $s->date?->format('Y-m-d') ?? '-';
                })
                ->addColumn('created_at', function ($s) {
                    return $s->created_at?->format('Y-m-d H:i:s') ?? '-';
                })
                ->addColumn('customer', fn($s) => $s->customer?->name ?? '-')
                ->addColumn('grand_total', fn($s) => number_format($s->grand_total, 2, ',', '.'))
                ->addColumn(
                    'payment_type',
                    fn($s) =>
                    $s->payment_type === 'CASH'
                        ? '<span class="badge bg-success">Cash</span>'
                        : '<span class="badge bg-warning">Piutang</span>'
                )
                ->addColumn(
                    'status',
                    fn($s) =>
                    "<span class='badge bg-" .
                        match ($s->status) {
                            'PAID' => 'success',
                            'PARTIAL' => 'warning',
                            default => 'secondary'
                        } . "'>{$s->status}</span>"
                )
                ->addColumn(
                    'action',
                    fn($s) =>
                    view('sales._column_action', compact('s'))->render()
                )
                ->rawColumns(['payment_type', 'status', 'action'])
                ->toJson();
        }

        return view('sales.index');
    }

    public function create()
    {
        return view('sales.form', [
            'sale'            => new Sale(),
            'invoice_preview' => 'SO-' . now()->format('Y-m') . '-XXX',
            'customers'       => Customer::orderBy('name')->get(),
            'purchases'       => Purchase::where('is_used', false)->get(),
            'cashAccounts'    => Account::where('code', 'like', '11%')->get(),
            'mode'            => 'create',
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'date'          => 'required|date',
            'customer_id'   => 'nullable|exists:customers,id',
            'payment_type'  => 'required|in:CASH,CREDIT',
            'due_date'      => 'required_if:payment_type,CREDIT|nullable|date',

            'subtotal'      => 'required|numeric|min:0',
            'discount'      => 'nullable|numeric|min:0',
            'tax'           => 'nullable|numeric|min:0',
            'grand_total'   => 'required|numeric|min:0',

            'items'                         => 'required|array|min:1',
            'items.*.purchase_item_id'      => 'required|exists:purchase_items,id',
            'items.*.product_id'            => 'required|exists:products,id',
            'items.*.qty'                   => 'required|numeric|min:0.0001',
            'items.*.price'                 => 'required|numeric|min:0',
            'items.*.subtotal'              => 'required|numeric|min:0',

            'cash_account_id' => 'required_if:payment_type,CASH|nullable|exists:accounts,id',
            // 'payment_method_id' => 'required_if:payment_type,CASH|nullable|exists:payment_methods,id',
        ]);

        // =========================
        // AMBIL PURCHASE IDS
        // =========================
        $purchaseItemIds = collect($validated['items'])
            ->pluck('purchase_item_id')
            ->unique();

        $purchaseIds = \App\Models\PurchaseItem::whereIn('id', $purchaseItemIds)
            ->pluck('purchase_id')
            ->unique();

        DB::transaction(function () use ($validated, $purchaseIds) {

            // =========================
            // LOCK PURCHASE
            // =========================
            $purchases = Purchase::whereIn('id', $purchaseIds)
                ->lockForUpdate()
                ->get();

            foreach ($purchases as $purchase) {
                if ($purchase->is_used) {
                    throw new \Exception(
                        "Purchase {$purchase->invoice_number} sudah digunakan"
                    );
                }
            }

            // =========================
            // SALE HEADER
            // =========================
            $sale = Sale::create([
                'invoice_number' => SaleInvoiceService::generate(),
                'date'           => $validated['date'],
                'customer_id'    => $validated['customer_id'],
                'payment_type'   => $validated['payment_type'],
                'due_date'       => $validated['payment_type'] === 'CREDIT'
                    ? $validated['due_date']
                    : null,
                'subtotal'       => $validated['subtotal'],
                'discount'       => $validated['discount'] ?? 0,
                'tax'            => $validated['tax'] ?? 0,
                'grand_total'    => $validated['grand_total'],
                'status'         => $validated['payment_type'] === 'CASH'
                    ? 'PAID'
                    : 'OPEN',
                'created_by'     => auth()->id(),
            ]);

            // =========================
            // SALE ITEMS
            // =========================
            foreach ($validated['items'] as $item) {
                SaleItem::create([
                    'sale_id'          => $sale->id,
                    'purchase_item_id' => $item['purchase_item_id'],
                    'product_id'       => $item['product_id'],
                    'qty'              => $item['qty'],
                    'price'            => $item['price'],
                    'discount'         => 0,
                    'subtotal'         => $item['subtotal'],
                ]);
            }

            // =========================
            // MARK PURCHASE USED
            // =========================
            Purchase::whereIn('id', $purchaseIds)
                ->update(['is_used' => true]);

            // =========================
            // ACCOUNTING SETUP
            // =========================
            $akunPenjualan = Account::where('code', '4101')->firstOrFail(); // Penjualan
            $akunPiutang   = Account::where('code', '1103')->firstOrFail(); // Piutang Usaha

            // =========================
            // JOURNAL HEADER
            // =========================
            $journal = Journal::create([
                'date'           => $sale->date,
                'reference_type' => 'SALE',
                'reference_id'   => $sale->id,
                'description'    => 'Penjualan #' . $sale->invoice_number,
                'created_by'     => auth()->id(),
            ]);

            // CREDIT PENJUALAN
            JournalDetail::create([
                'journal_id' => $journal->id,
                'account_id' => $akunPenjualan->id,
                'debit'      => 0,
                'credit'     => $sale->grand_total,
            ]);

            // =========================
            // CASH SALE
            // =========================
            if ($sale->payment_type === 'CASH') {

                $akunKas = Account::findOrFail($validated['cash_account_id']);

                // PAYMENT
                Payment::create([
                    'reference_type'    => 'SALE',
                    'reference_id'      => $sale->id,
                    // 'payment_method_id' => $validated['payment_method_id'],
                    'cash_account_id'   => $validated['cash_account_id'],
                    'amount'            => $sale->grand_total,
                    'date'              => $sale->date,
                    'created_by'        => auth()->id(),
                ]);

                // DEBIT KAS
                JournalDetail::create([
                    'journal_id' => $journal->id,
                    'account_id' => $akunKas->id,
                    'debit'      => $sale->grand_total,
                    'credit'     => 0,
                ]);
            }

            // =========================
            // CREDIT SALE
            // =========================
            if ($sale->payment_type === 'CREDIT') {

                if (!$sale->customer_id) {
                    throw new \Exception('Customer wajib diisi untuk penjualan kredit');
                }

                // CUSTOMER RECEIVABLE
                CustomerReceivable::create([
                    'sale_id'     => $sale->id,
                    'customer_id' => $sale->customer_id,
                    'total'       => $sale->grand_total,
                    'paid'        => 0,
                    'balance'     => $sale->grand_total,
                    'status'      => 'OPEN',
                ]);

                // DEBIT PIUTANG
                JournalDetail::create([
                    'journal_id' => $journal->id,
                    'account_id' => $akunPiutang->id,
                    'debit'      => $sale->grand_total,
                    'credit'     => 0,
                ]);
            }
        });

        return redirect()
            ->route('sales.index')
            ->with('success', 'Penjualan berhasil disimpan');
    }

    public function show(int $id)
    {
        $sale = Sale::with([
            'customer',
            'items.product',
            'items.purchaseItem.purchase',
            'payments.cashAccount',
            'receivable',
        ])->findOrFail($id);

        return view('sales.show', compact('sale'));
    }

    public function print(int $id)
    {
        $sale = Sale::with([
            'customer',
            'items.product',
            'receivable',
        ])->findOrFail($id);

        return view('sales.print', compact('sale'));
    }
}
