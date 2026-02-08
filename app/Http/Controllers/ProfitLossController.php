<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Expense;
use App\Models\Customer;
use App\Models\Purchase;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ProfitLossController extends Controller
{
    /* =========================
     * LABA RUGI PER PENJUALAN
     * ========================= */
    public function perSale(Request $request)
    {
        $saleId = $request->get('sale_id');

        $sale = null;
        $result = null;
        $purchases = collect();
        $expenses = collect();

        if ($saleId) {

            $sale = Sale::with([
                'customer',
                'items.product',
                'items.purchaseItem.purchase',
            ])->findOrFail($saleId);

            // =========================
            // TOTAL PENJUALAN
            // =========================
            $totalPenjualan = $sale->grand_total;

            // =========================
            // PURCHASE IDS DARI SALE
            // =========================
            $purchaseIds = $sale->items
                ->pluck('purchaseItem.purchase_id')
                ->unique()
                ->filter();

            // =========================
            // DETAIL PEMBELIAN
            // =========================
            $purchases = Purchase::with(['items.product'])
                ->whereIn('id', $purchaseIds)
                ->get();

            $totalPembelian = $purchases->sum('grand_total');

            // =========================
            // EXPENSE DARI PEMBELIAN
            // =========================
            $expenses = Expense::with('items')
                ->whereIn('purchase_id', $purchaseIds)
                ->get();

            $totalExpense = $expenses->sum('grand_total');

            // =========================
            // LABA
            // =========================
            $laba = $totalPenjualan - $totalPembelian - $totalExpense;

            $result = compact(
                'totalPenjualan',
                'totalPembelian',
                'totalExpense',
                'laba'
            );
        }

        return view('profit-loss.per-sale', [
            'sales'     => Sale::orderBy('invoice_number')->get(),
            'sale'      => $sale,
            'purchases' => $purchases,
            'expenses'  => $expenses,
            'result'    => $result,
        ]);
    }

    /* =========================
     * LABA RUGI PER CUSTOMER + PERIODE
     * ========================= */
    public function perCustomer(Request $request)
    {
        $customerId = $request->get('customer_id');
        $from       = $request->get('from_date');
        $to         = $request->get('to_date');

        $result    = null;
        $sales     = collect();
        $purchases = collect();
        $expenses  = collect();

        if ($customerId && $from && $to) {

            // =========================
            // SALES
            // =========================
            $sales = Sale::with([
                'items.product',
                'items.purchaseItem.purchase',
            ])
                ->where('customer_id', $customerId)
                ->whereBetween('date', [$from, $to])
                ->orderBy('date')
                ->get();

            $totalPenjualan = $sales->sum('grand_total');

            // =========================
            // PURCHASE IDS
            // =========================
            $purchaseIds = $sales->flatMap(function ($sale) {
                return $sale->items->pluck('purchaseItem.purchase_id');
            })
                ->unique()
                ->filter();

            // =========================
            // PURCHASES + ITEMS
            // =========================
            $purchases = Purchase::with(['items.product'])
                ->whereIn('id', $purchaseIds)
                ->get();

            $totalPembelian = $purchases->sum('grand_total');

            // =========================
            // EXPENSES + ITEMS
            // =========================
            $expenses = Expense::with(['items', 'purchase'])
                ->whereIn('purchase_id', $purchaseIds)
                ->get();

            $totalExpense = $expenses->sum('grand_total');

            // =========================
            // LABA
            // =========================
            $laba = $totalPenjualan - $totalPembelian - $totalExpense;

            $result = compact(
                'totalPenjualan',
                'totalPembelian',
                'totalExpense',
                'laba'
            );
        }

        return view('profit-loss.per-customer', [
            'customers' => Customer::orderBy('name')->get(),
            'sales'     => $sales,
            'purchases' => $purchases,
            'expenses'  => $expenses,
            'result'    => $result,
            'from'      => $from,
            'to'        => $to,
            'selectedCustomer' => $customerId,
        ]);
    }
}
