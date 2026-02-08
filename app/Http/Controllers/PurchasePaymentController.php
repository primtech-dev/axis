<?php

namespace App\Http\Controllers;

use App\Models\Purchase;
use App\Models\Payment;
use App\Models\SupplierPayable;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Journal;
use App\Models\JournalDetail;
use App\Models\Account;

class PurchasePaymentController extends Controller
{
    /* =========================
     * FORM BAYAR HUTANG
     * ========================= */
    public function create(Purchase $purchase)
    {
        abort_if(
            $purchase->payment_type !== 'CREDIT' || $purchase->status === 'PAID',
            403
        );

        $payable = $purchase->payable;

        return view('purchases.pay', [
            'purchase'     => $purchase,
            'payable'      => $payable,

            // 🔥 akun kas / bank aktif
            'cashAccounts' => Account::where('type', 'ASSET')
                ->where('is_active', true)
                ->orderBy('code')
                ->get(),
        ]);
    }

    /* =========================
     * SIMPAN PEMBAYARAN
     * ========================= */
    public function store(Request $request, Purchase $purchase)
    {
        abort_if(
            $purchase->payment_type !== 'CREDIT' || $purchase->status === 'PAID',
            403
        );

        $payable = $purchase->payable;

        $validated = $request->validate([
            'date'            => 'required|date',
            'cash_account_id' => 'required|exists:accounts,id',
            'amount'          => 'required|numeric|min:1|max:' . $payable->balance,
        ]);

        DB::transaction(function () use ($validated, $purchase, $payable) {

            /* =========================
             * PAYMENT
             * ========================= */
            $payment = Payment::create([
                'reference_type'   => 'PURCHASE',
                'reference_id'     => $purchase->id,
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
             * UPDATE PURCHASE STATUS
             * ========================= */
            if ($payable->balance <= 0) {
                $purchase->status = 'PAID';
            } else {
                $purchase->status = 'PARTIAL';
            }
            $purchase->save();

            /* =========================
            * JOURNAL PAYMENT HUTANG
            * ========================= */

            // akun utang
            $akunUtang = Account::where('code', '2101')->firstOrFail();

            // header jurnal
            $journal = Journal::create([
                'date'           => $payment->date,
                'reference_type' => 'PAYMENT',
                'reference_id'   => $payment->id,
                'description'    => 'Pembayaran hutang pembelian #' . $purchase->invoice_number,
                'created_by'     => auth()->id(),
            ]);

            // DR Utang Usaha
            JournalDetail::create([
                'journal_id' => $journal->id,
                'account_id' => $akunUtang->id,
                'debit'      => $payment->amount,
                'credit'     => 0,
            ]);

            // CR Kas / Bank (akun dipilih user)
            JournalDetail::create([
                'journal_id' => $journal->id,
                'account_id' => $payment->cash_account_id,
                'debit'      => 0,
                'credit'     => $payment->amount,
            ]);
        });

        return redirect()
            ->route('purchases.show', $purchase->id)
            ->with('success', 'Pembayaran hutang berhasil disimpan');
    }

    public function print(Payment $payment)
    {
        abort_if($payment->reference_type !== 'PURCHASE', 404);

        $purchase = Purchase::with('supplier')->findOrFail($payment->reference_id);

        return view('purchases.print-payment', [
            'payment'  => $payment,
            'purchase' => $purchase,
        ]);
    }
}
