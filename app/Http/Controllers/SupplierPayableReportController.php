<?php

namespace App\Http\Controllers;

use App\Models\SupplierPayable;
use App\Models\Supplier;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\SupplierPayableExport;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class SupplierPayableReportController extends Controller
{
    public function index(Request $request)
    {
        $suppliers = Supplier::orderBy('name')->get();

        $query = SupplierPayable::with(['supplier', 'purchase'])
            ->where('balance', '>', 0);

        // Filter tanggal (pakai tanggal transaksi purchase)
        if ($request->start_date && $request->end_date) {
            $query->whereHas('purchase', function ($q) use ($request) {
                $q->whereBetween('date', [
                    $request->start_date,
                    $request->end_date
                ]);
            });
        }

        // Filter supplier (multiple)
        if ($request->supplier_ids && count($request->supplier_ids)) {
            $query->whereIn('supplier_id', $request->supplier_ids);
        }

        $payables = $query->latest()->get();

        return view('reports.supplier_payables_index', compact(
            'payables',
            'suppliers'
        ));
    }

    public function pdf(Request $request)
    {
        $query = SupplierPayable::with(['supplier', 'purchase'])
            ->where('balance', '>', 0);

        // Filter tanggal
        if ($request->start_date && $request->end_date) {
            $query->whereHas('purchase', function ($q) use ($request) {
                $q->whereBetween('date', [
                    $request->start_date,
                    $request->end_date
                ]);
            });
        }

        // Filter supplier
        if ($request->supplier_ids && count($request->supplier_ids)) {
            $query->whereIn('supplier_id', $request->supplier_ids);
        }

        $payables = $query->latest()->get();

        $grandTotal = $payables->sum('total');
        $grandPaid = $payables->sum('paid');
        $grandBalance = $payables->sum('balance');

        return Pdf::loadView(
            'reports.supplier_payables_pdf',
            compact('payables', 'grandTotal', 'grandPaid', 'grandBalance')
        )->download('laporan-hutang-supplier.pdf');
    }

    public function excel(Request $request)
    {
        return Excel::download(
            new SupplierPayableExport($request),
            'laporan-hutang-supplier.xlsx'
        );
    }
}
