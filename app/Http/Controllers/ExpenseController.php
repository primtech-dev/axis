<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\ExpenseItem;
use App\Models\Purchase;
use App\Models\Supplier;
use App\Models\Journal;
use App\Models\JournalDetail;
use App\Models\Account;
use App\Models\Payment;
use App\Models\SupplierPayable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Services\ExpenseNumberService;

class ExpenseController extends Controller
{
    /* =========================
     * INDEX
     * ========================= */
    public function index(Request $request)
    {
        if ($request->ajax()) {

            $query = Expense::query()
                ->with(['purchase', 'supplier'])
                ->select('expenses.*');

            return datatables()->eloquent($query)
                ->addIndexColumn()

                ->addColumn(
                    'date',
                    fn($e) =>
                    $e->date?->format('Y-m-d') ?? '-'
                )

                ->addColumn(
                    'purchase',
                    fn($e) =>
                    $e->purchase?->invoice_number ?? '-'
                )

                ->addColumn(
                    'supplier',
                    fn($e) =>
                    $e->supplier?->name ?? '-'
                )

                ->addColumn(
                    'grand_total',
                    fn($e) =>
                    number_format($e->grand_total, 2, ',', '.')
                )

                ->addColumn('status', function ($e) {
                    $color = $e->status === 'PAID' ? 'success' : 'warning';
                    return "<span class='badge bg-{$color}'>{$e->status}</span>";
                })

                ->addColumn(
                    'created_at',
                    fn($e) =>
                    $e->created_at?->format('Y-m-d H:i:s')
                )

                ->addColumn(
                    'action',
                    fn($e) =>
                    view('expenses._column_action', compact('e'))->render()
                )

                ->rawColumns(['status', 'action'])
                ->toJson();
        }

        return view('expenses.index');
    }

    /* =========================
     * CREATE
     * ========================= */
    public function create()
    {
        return view('expenses.form', [
            'expense'   => new Expense(),
            'purchases' => Purchase::orderByDesc('date')->get(),
            'suppliers' => Supplier::where('type', 'EXPENSE')
                ->where('is_active', true)
                ->orderBy('name')
                ->get(),
            'cashAccounts' => Account::where('type', 'ASSET')
                ->where('code', 'like', '11%')
                ->where('is_active', true)
                ->orderBy('code')
                ->get(),
        ]);
    }

    /* =========================
     * STORE
     * ========================= */
    public function store(Request $request)
    {
        // dd($request->all());
        $validated = $request->validate([
            'purchase_id'   => 'required|exists:purchases,id',
            'supplier_id'   => 'required|exists:suppliers,id',
            'date'          => 'required|date',
            'payment_type'  => 'required|in:CASH,CREDIT',
            'due_date'      => 'required_if:payment_type,CREDIT|nullable|date',

            'subtotal'      => 'required|numeric|min:0',
            'grand_total'   => 'required|numeric|min:0',

            'cash_account_id' => 'required_if:payment_type,CASH|nullable|exists:accounts,id',

            'items' => 'required|array|min:1',
            'items.*.name' => 'required|string|max:150',
            'items.*.qty' => 'required|numeric|min:0.0001',
            'items.*.price' => 'required|numeric|min:0',
            'items.*.subtotal' => 'required|numeric|min:0',
        ]);

        DB::transaction(function () use ($validated) {

            /* =========================
            * EXPENSE HEADER
            * ========================= */
            $expense = Expense::create([
                'expense_number' => ExpenseNumberService::generate(),
                'purchase_id' => $validated['purchase_id'],
                'supplier_id' => $validated['supplier_id'],
                'date' => $validated['date'],
                'payment_type' => $validated['payment_type'],
                'due_date' => $validated['payment_type'] === 'CREDIT'
                    ? $validated['due_date']
                    : null,
                'subtotal' => $validated['subtotal'],
                'grand_total' => $validated['grand_total'],
                'status' => $validated['payment_type'] === 'CASH'
                    ? 'PAID'
                    : 'OPEN',
                'created_by' => auth()->id(),
            ]);

            /* =========================
            * EXPENSE ITEMS
            * ========================= */
            foreach ($validated['items'] as $item) {
                ExpenseItem::create([
                    'expense_id' => $expense->id,
                    'name' => $item['name'],
                    'qty' => $item['qty'],
                    'price' => $item['price'],
                    'subtotal' => $item['subtotal'],
                ]);
            }

            /* =========================
            * JOURNAL
            * ========================= */
            $akunBiaya = Account::where('code', '5101')->firstOrFail(); // Biaya Pembelian
            $akunUtang = Account::where('code', '2101')->firstOrFail(); // Utang Usaha

            $journal = Journal::create([
                'date' => $expense->date,
                'reference_type' => 'EXPENSE',
                'reference_id' => $expense->id,
                'description' => 'Biaya Pembelian #' . $expense->expense_number,
                'created_by' => auth()->id(),
            ]);

            // DEBIT → BIAYA
            JournalDetail::create([
                'journal_id' => $journal->id,
                'account_id' => $akunBiaya->id,
                'debit' => $expense->grand_total,
                'credit' => 0,
            ]);

            // CREDIT → CASH / BANK
            if ($expense->payment_type === 'CASH') {

                Payment::create([
                    'reference_type'     => 'EXPENSE',
                    'reference_id'       => $expense->id,
                    'payment_method_id'  => null, // atau ambil dari form kalau ada
                    'amount'             => $expense->grand_total,
                    'date'               => $expense->date,
                    'created_by'         => auth()->id(),
                    'cash_account_id'    => $validated['cash_account_id'],
                ]);

                JournalDetail::create([
                    'journal_id' => $journal->id,
                    'account_id' => $validated['cash_account_id'],
                    'debit' => 0,
                    'credit' => $expense->grand_total,
                ]);
            }

            // CREDIT → UTANG
            if ($expense->payment_type === 'CREDIT') {

                JournalDetail::create([
                    'journal_id' => $journal->id,
                    'account_id' => $akunUtang->id,
                    'debit' => 0,
                    'credit' => $expense->grand_total,
                ]);

                SupplierPayable::create([
                    'reference_type' => 'EXPENSE',
                    'reference_id'   => $expense->id,
                    'supplier_id'    => $expense->supplier_id,
                    'total'          => $expense->grand_total,
                    'paid'           => 0,
                    'balance'        => $expense->grand_total,
                    'status'         => 'OPEN',
                ]);
            }
        });


        return redirect()
            ->route('expenses.index')
            ->with('success', 'Biaya berhasil disimpan');
    }

    /* =========================
     * SHOW
     * ========================= */
    public function show(int $id)
    {
        $expense = Expense::with([
            'supplier',
            'purchase',
            'items',
            'payable',     // supplier_payables (reference EXPENSE)
            'payments.cashAccount', // future-proof
        ])->findOrFail($id);

        return view('expenses.show', compact('expense'));
    }

    public function print(int $id)
    {
        $expense = Expense::with([
            'supplier',
            'purchase',
            'items',
            'payable',
        ])->findOrFail($id);

        return view('expenses.print', compact('expense'));
    }
}
