<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CustomerReceivable;
use App\Models\Customer;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\CustomerReceivableExport;

class CustomerReceivableReportController extends Controller
{
    public function index(Request $request)
    {
        $customers = Customer::orderBy('name')->get();

        $query = CustomerReceivable::with(['customer', 'sale'])
            ->where('balance', '>', 0);

        // Filter tanggal
        if ($request->start_date && $request->end_date) {
            $query->whereHas('sale', function ($q) use ($request) {
                $q->whereBetween('date', [
                    $request->start_date,
                    $request->end_date
                ]);
            });
        }

        // Filter customer
        if ($request->customer_ids && count($request->customer_ids)) {
            $query->whereIn('customer_id', $request->customer_ids);
        }

        $receivables = $query->latest()->get();

        return view('reports.customer_receivables_index', compact(
            'receivables',
            'customers'
        ));
    }

    public function pdf(Request $request)
    {
        $query = CustomerReceivable::with(['customer', 'sale'])
            ->where('balance', '>', 0);

        if ($request->start_date && $request->end_date) {
            $query->whereHas('sale', function ($q) use ($request) {
                $q->whereBetween('date', [
                    $request->start_date,
                    $request->end_date
                ]);
            });
        }

        if ($request->customer_ids && count($request->customer_ids)) {
            $query->whereIn('customer_id', $request->customer_ids);
        }

        $receivables = $query->get();

        $grandTotal = $receivables->sum('total');
        $grandPaid = $receivables->sum('paid');
        $grandBalance = $receivables->sum('balance');

        return Pdf::loadView(
            'reports.customer_receivables_pdf',
            compact('receivables', 'grandTotal', 'grandPaid', 'grandBalance')
        )->download('laporan-piutang-customer.pdf');
    }

    public function excel(Request $request)
    {
        return Excel::download(
            new CustomerReceivableExport($request),
            'laporan-piutang-customer.xlsx'
        );
    }
}
