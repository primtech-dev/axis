<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Payment;
use App\Models\CustomerReceivable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Journal;
use App\Models\JournalDetail;
use App\Models\Account;

class SalePaymentController extends Controller
{
    /* =========================
     * FORM BAYAR PIUTANG
     * ========================= */
    public function create(Sale $sale)
    {
        abort_if(
            $sale->payment_type !== 'CREDIT' || $sale->status === 'PAID',
            403
        );

        $receivable = $sale->receivable;

        return view('sales.pay', [
            'sale'        => $sale,
            'receivable'  => $receivable,
            'cashAccounts' => Account::where('type', 'ASSET')
                ->where('is_active', true)
                ->orderBy('code')
                ->get(),
        ]);
    }

    /* =========================
     * SIMPAN PEMBAYARAN PIUTANG
     * ========================= */
    public function store(Request $request, Sale $sale)
    {
        abort_if(
            $sale->payment_type !== 'CREDIT' || $sale->status === 'PAID',
            403
        );

        $receivable = $sale->receivable;

        $validated = $request->validate([
            'date'            => 'required|date',
            'cash_account_id' => 'required|exists:accounts,id',
            'amount'          => 'required|numeric|min:1|max:' . $receivable->balance,
        ]);

        DB::transaction(function () use ($validated, $sale, $receivable) {

            /* =========================
             * PAYMENT
             * ========================= */
            $payment = Payment::create([
                'reference_type'  => 'SALE',
                'reference_id'    => $sale->id,
                'cash_account_id' => $validated['cash_account_id'],
                'amount'          => $validated['amount'],
                'date'            => $validated['date'],
                'created_by'      => auth()->id(),
            ]);

            /* =========================
             * UPDATE RECEIVABLE
             * ========================= */
            $receivable->paid += $validated['amount'];
            $receivable->balance = $receivable->total - $receivable->paid;
            $receivable->status = $receivable->balance <= 0 ? 'PAID' : 'OPEN';
            $receivable->save();

            /* =========================
             * UPDATE SALE STATUS
             * ========================= */
            if ($receivable->balance <= 0) {
                $sale->status = 'PAID';
            } else {
                $sale->status = 'PARTIAL';
            }
            $sale->save();

            /* =========================
             * JOURNAL PAYMENT PIUTANG
             * ========================= */

            // akun piutang usaha (punyamu: 1103)
            $akunPiutang = Account::where('code', '1103')->firstOrFail();

            $journal = Journal::create([
                'date'           => $payment->date,
                'reference_type' => 'PAYMENT',
                'reference_id'   => $payment->id,
                'description'    => 'Pembayaran piutang penjualan #' . $sale->invoice_number,
                'created_by'     => auth()->id(),
            ]);

            // DR Kas / Bank
            JournalDetail::create([
                'journal_id' => $journal->id,
                'account_id' => $payment->cash_account_id,
                'debit'      => $payment->amount,
                'credit'     => 0,
            ]);

            // CR Piutang Usaha
            JournalDetail::create([
                'journal_id' => $journal->id,
                'account_id' => $akunPiutang->id,
                'debit'      => 0,
                'credit'     => $payment->amount,
            ]);
        });

        return redirect()
            ->route('sales.show', $sale->id)
            ->with('success', 'Pembayaran piutang berhasil disimpan');
    }

    public function print(Payment $payment)
    {
        abort_if($payment->reference_type !== 'SALE', 404);

        $sale = Sale::with('customer')->findOrFail($payment->reference_id);

        return view('sales.print-payment', [
            'payment' => $payment,
            'sale'    => $sale,
        ]);
    }
}
