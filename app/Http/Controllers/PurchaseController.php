<?php

namespace App\Http\Controllers;

use App\Models\Purchase;
use App\Models\PurchaseItem;
use App\Models\Supplier;
use App\Models\Product;
use App\Models\Payment;
use App\Models\PaymentMethod;
use App\Models\SupplierPayable;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Journal;
use App\Models\JournalDetail;
use App\Models\Account;

use App\Services\PurchaseInvoiceService;

class PurchaseController extends Controller
{
    /* =========================
     * INDEX
     * ========================= */
    public function index(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {

            $query = Purchase::query()
                ->with('supplier')
                ->select([
                    'purchases.id',
                    'purchases.invoice_number',
                    'purchases.date',
                    'purchases.supplier_id',
                    'purchases.payment_type',
                    'purchases.grand_total',
                    'purchases.status',
                    'purchases.created_at',
                ]);

            return datatables()->eloquent($query)
                ->addIndexColumn()
                ->addColumn('date', function ($p) {
                    return $p->date
                        ? $p->date->format('Y-m-d')
                        : '-';
                })
                ->addColumn('grand_total', function ($p) {
                    return number_format($p->grand_total, 2, ',', '.');
                })

                ->addColumn('supplier', function ($p) {
                    return $p->supplier?->name ?? '-';
                })

                ->addColumn('payment_type', function ($p) {
                    return $p->payment_type === 'CASH'
                        ? '<span class="badge bg-success">Cash</span>'
                        : '<span class="badge bg-warning">Hutang</span>';
                })

                ->addColumn('status', function ($p) {
                    $color = match ($p->status) {
                        'PAID'    => 'success',
                        'PARTIAL' => 'warning',
                        default   => 'secondary',
                    };

                    return "<span class='badge bg-{$color}'>{$p->status}</span>";
                })

                ->addColumn('created_at', function ($p) {
                    return $p->created_at
                        ? $p->created_at->format('Y-m-d H:i:s')
                        : '-';
                })

                ->addColumn('action', function ($p) {
                    return view('purchases._column_action', ['p' => $p])->render();
                })

                ->rawColumns(['payment_type', 'status', 'action'])
                ->toJson();
        }

        return view('purchases.index');
    }

    /* =========================
     * CREATE
     * ========================= */
    public function create()
    {
        return view('purchases.form', [
            'purchase'        => new Purchase(),
            'invoice_preview' => 'PO-' . now()->format('Y-m') . '-XXX',
            'suppliers'       => Supplier::orderBy('name')->where('type', 'PURCHASE')->get(),
            'products'        => Product::orderBy('name')->get(),
            'methods'         => PaymentMethod::where('is_active', true)->get(),
            'cashAccounts' => Account::where('type', 'ASSET')
                ->where('code', 'like', '11%')
                ->where('is_active', true)
                ->orderBy('code')
                ->get(),
            'mode'            => 'create',
        ]);
    }


    /* =========================
     * STORE
     * ========================= */
    public function store(Request $request)
    {
        // dd($request->all());

        $validated = $request->validate([
            // 'invoice_number'          => 'required|string|max:50|unique:purchases,invoice_number',
            'date'                    => 'required|date',
            'supplier_id'             => 'required|exists:suppliers,id',
            'payment_type'            => 'required|in:CASH,CREDIT',

            'due_date'     => 'required_if:payment_type,CREDIT|nullable|date',

            'subtotal'                => 'required|numeric|min:0',
            'discount'                => 'nullable|numeric|min:0',
            'tax'                     => 'nullable|numeric|min:0',
            'grand_total'             => 'required|numeric|min:0',

            'items'                   => 'required|array|min:1',
            'items.*.product_id'      => 'required|exists:products,id',
            'items.*.qty'             => 'required|numeric|min:0.0001',
            'items.*.price'           => 'required|numeric|min:0',
            'items.*.subtotal'        => 'required|numeric|min:0',

            'cash_account_id'   => 'required_if:payment_type,CASH|nullable|exists:accounts,id',
        ]);

        DB::transaction(function () use ($validated, $request) {

            /* =========================
             * PURCHASE HEADER
             * ========================= */
            $invoiceNumber = PurchaseInvoiceService::generate();

            $purchase = Purchase::create([
                'invoice_number' => $invoiceNumber,
                'date'           => $validated['date'],
                'supplier_id'    => $validated['supplier_id'],
                'payment_type'   => $validated['payment_type'],
                'due_date'       => $validated['payment_type'] === 'CREDIT'
                    ? $validated['due_date']
                    : null,
                'subtotal'       => $validated['subtotal'],
                'discount'       => $validated['discount'] ?? 0,
                'tax'            => $validated['tax'] ?? 0,
                'grand_total'    => $validated['grand_total'],
                'status'         => $validated['payment_type'] === 'CASH' ? 'PAID' : 'OPEN',
                'created_by'     => auth()->id(),
            ]);

            // =========================
            // JOURNAL ENTRIES
            // =========================

            $akunPembelian = Account::where('code', '5101')->firstOrFail(); // Biaya Pembelian
            $akunUtang     = Account::where('code', '2101')->firstOrFail(); // Utang Usaha
            $akunKas       = Account::where('code', '1101')->firstOrFail(); // Kas
            $akunBank      = Account::where('code', '1102')->firstOrFail(); // Bank

            $journal = Journal::create([
                'date'           => $purchase->date,
                'reference_type' => 'PURCHASE',
                'reference_id'   => $purchase->id,
                'description'    => 'Pembelian #' . $purchase->invoice_number,
                'created_by'     => auth()->id(),
            ]);

            JournalDetail::create([
                'journal_id' => $journal->id,
                'account_id' => $akunPembelian->id,
                'debit'      => $purchase->grand_total,
                'credit'     => 0,
            ]);

            if ($purchase->payment_type === 'CASH') {

                JournalDetail::create([
                    'journal_id' => $journal->id,
                    'account_id' => $validated['cash_account_id'],
                    'debit'      => 0,
                    'credit'     => $purchase->grand_total,
                ]);
            }

            if ($purchase->payment_type === 'CREDIT') {

                JournalDetail::create([
                    'journal_id' => $journal->id,
                    'account_id' => $akunUtang->id,
                    'debit'      => 0,
                    'credit'     => $purchase->grand_total,
                ]);
            }

            // END JOURNAL ENTRIES

            /* =========================
             * ITEMS + STOCK IN
             * ========================= */
            foreach ($validated['items'] as $item) {

                $purchaseItem = PurchaseItem::create([
                    'purchase_id' => $purchase->id,
                    'product_id'  => $item['product_id'],
                    'qty'         => $item['qty'],
                    'price'       => $item['price'],
                    'subtotal'    => $item['subtotal'],
                ]);

                StockMovement::create([
                    'product_id'  => $item['product_id'],
                    'qty'         => $item['qty'], // POSITIF (IN)
                    'type'        => 'IN',
                    'source_type' => 'PURCHASE',
                    'source_id'   => $purchase->id,
                    'note'        => 'Purchase #' . $purchase->invoice_number,
                    'created_by'  => auth()->id(),
                ]);
            }

            /* =========================
             * CASH → PAYMENT
             * ========================= */
            if ($validated['payment_type'] === 'CASH') {

                Payment::create([
                    'reference_type'     => 'PURCHASE',
                    'reference_id'       => $purchase->id,
                    'cash_account_id'    => $validated['cash_account_id'],
                    'amount'             => $purchase->grand_total,
                    'date'               => $purchase->date,
                    'created_by'         => auth()->id(),
                ]);
            }

            /* =========================
             * CREDIT → SUPPLIER PAYABLE
             * ========================= */
            if ($validated['payment_type'] === 'CREDIT') {
                SupplierPayable::create([
                    'reference_type' => 'PURCHASE',
                    'reference_id'   => $purchase->id,
                    'supplier_id'    => $purchase->supplier_id,
                    'total'          => $purchase->grand_total,
                    'paid'           => 0,
                    'balance'        => $purchase->grand_total,
                    'status'         => 'OPEN',
                ]);
            }
        });

        return redirect()
            ->route('purchases.index')
            ->with('success', 'Pembelian berhasil disimpan');
    }

    /* =========================
     * SHOW (DETAIL)
     * ========================= */
    public function show(int $id)
    {
        $purchase = Purchase::with([
            'supplier',
            'items.product',
            'payable',
            'payments.paymentMethod',
        ])->findOrFail($id);

        return view('purchases.show', compact('purchase'));
    }

    public function print(int $id)
    {
        $purchase = Purchase::with([
            'supplier',
            'items.product',
            'payable',
        ])->findOrFail($id);

        return view('purchases.print', compact('purchase'));
    }
}
