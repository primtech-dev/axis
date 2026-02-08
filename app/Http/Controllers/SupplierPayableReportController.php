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
    public function index()
    {
        $suppliers = Supplier::withSum(
            ['payables as total_hutang' => function ($q) {
                $q->where('balance', '>', 0);
            }],
            'balance'
        )->get();

        return view('reports.supplier_payables_index', compact('suppliers'));
    }

    public function pdf(Request $request)
    {
        $query = SupplierPayable::with(['supplier', 'purchase'])
            ->where('balance', '>', 0);

        if ($request->supplier_id) {
            $query->where('supplier_id', $request->supplier_id);
        }

        $payables = $query->get();

        return Pdf::loadView(
            'reports.supplier_payables_pdf',
            compact('payables')
        )->download('laporan-hutang-supplier.pdf');
    }

    public function excel()
    {
        return Excel::download(
            new SupplierPayableExport,
            'laporan-hutang-supplier.xlsx'
        );
    }
}
