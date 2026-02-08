<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\Payment;
use App\Models\SupplierPayable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Journal;
use App\Models\JournalDetail;
use App\Models\Account;

class ExpensePaymentController extends Controller
{
    /* =========================
     * FORM BAYAR HUTANG BIAYA
     * ========================= */
    public function create(Expense $expense)
    {
        abort_if(
            $expense->payment_type !== 'CREDIT' || $expense->status === 'PAID',
            403
        );

        $payable = $expense->payable;

        return view('expenses.pay', [
            'expense'      => $expense,
            'payable'      => $payable,

            // akun kas / bank aktif
            'cashAccounts' => Account::where('type', 'ASSET')
                ->where('is_active', true)
                ->orderBy('code')
                ->get(),
        ]);
    }

    /* =========================
     * SIMPAN PEMBAYARAN HUTANG
     * ========================= */
    public function store(Request $request, Expense $expense)
    {
        abort_if(
            $expense->payment_type !== 'CREDIT' || $expense->status === 'PAID',
            403
        );

        $payable = $expense->payable;

        $validated = $request->validate([
            'date'            => 'required|date',
            'cash_account_id' => 'required|exists:accounts,id',
            'amount'          => 'required|numeric|min:1|max:' . $payable->balance,
        ]);

        DB::transaction(function () use ($validated, $expense, $payable) {

            /* =========================
             * PAYMENT
             * ========================= */
            $payment = Payment::create([
                'reference_type'   => 'EXPENSE',
                'reference_id'     => $expense->id,
                'cash_account_id'  => $validated['cash_account_id'],
                'payment_method_id' => null, // legacy
                'amount'           => $validated['amount'],
                'date'             => $validated['date'],
                'created_by'       => auth()->id(),
            ]);

            /* =========================
             * UPDATE PAYABLE
             * ========================= */
            $payable->paid += $validated['amount'];
            $payable->balance = $payable->total - $payable->paid;
            $payable->status = $payable->balance <= 0 ? 'PAID' : 'OPEN';
            $payable->save();

            /* =========================
             * UPDATE EXPENSE STATUS
             * ========================= */
            if ($payable->balance <= 0) {
                $expense->status = 'PAID';
            } else {
                $expense->status = 'PARTIAL';
            }
            $expense->save();

            /* =========================
             * JOURNAL PAYMENT HUTANG
             * ========================= */

            // akun utang usaha
            $akunUtang = Account::where('code', '2101')->firstOrFail();

            // header jurnal
            $journal = Journal::create([
                'date'           => $payment->date,
                'reference_type' => 'PAYMENT',
                'reference_id'   => $payment->id,
                'description'    => 'Pembayaran hutang biaya #' . $expense->expense_number,
                'created_by'     => auth()->id(),
            ]);

            // DR Utang Usaha
            JournalDetail::create([
                'journal_id' => $journal->id,
                'account_id' => $akunUtang->id,
                'debit'      => $payment->amount,
                'credit'     => 0,
            ]);

            // CR Kas / Bank
            JournalDetail::create([
                'journal_id' => $journal->id,
                'account_id' => $payment->cash_account_id,
                'debit'      => 0,
                'credit'     => $payment->amount,
            ]);
        });

        return redirect()
            ->route('expenses.show', $expense->id)
            ->with('success', 'Pembayaran hutang biaya berhasil disimpan');
    }

    /* =========================
     * PRINT BUKTI PEMBAYARAN
     * ========================= */
    public function print(Payment $payment)
    {
        abort_if($payment->reference_type !== 'EXPENSE', 404);

        $expense = Expense::with('supplier')
            ->findOrFail($payment->reference_id);

        return view('expenses.print-payment', [
            'payment' => $payment,
            'expense' => $expense,
        ]);
    }
}
