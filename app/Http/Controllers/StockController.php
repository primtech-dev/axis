<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\StockExport;
use App\Exports\StockCardExport;
use Carbon\Carbon;

class StockController extends Controller
{
    /* =========================
     * LIST STOK
     * ========================= */
    public function index(Request $request)
    {
        if ($request->ajax()) {

            $query = DB::table('products as p')
                ->join('units as u', 'u.id', '=', 'p.unit_id')
                ->select(
                    'p.id',
                    'p.sku',
                    'p.name',
                    'u.name as unit',
                    DB::raw('(
                        SELECT COALESCE(SUM(sm.qty), 0)
                        FROM stock_movements sm
                        WHERE sm.product_id = p.id
                    ) as stock')
                )
                ->where('p.is_active', 1);

            return datatables()
                ->of($query)
                ->addIndexColumn()
                ->editColumn(
                    'stock',
                    fn($r) =>
                    number_format($r->stock, 2, ',', '.')
                )
                ->addColumn('action', function ($r) {
                    return view('stocks._column_action', ['p' => $r])->render();
                })
                ->rawColumns(['action'])
                ->toJson();
        }

        return view('stocks.index');
    }

    /* =========================
     * KARTU STOK
     * ========================= */
    public function card(Request $request, Product $product)
    {
        $query = DB::table('stock_movements')
            ->where('product_id', $product->id);

        if ($request->filled('from')) {
            $query->whereDate('created_at', '>=', $request->from);
        }

        if ($request->filled('to')) {
            $query->whereDate('created_at', '<=', $request->to);
        }

        $movements = $query
            ->orderBy('created_at')
            ->get();

        // hitung saldo berjalan
        $saldo = 0;
        foreach ($movements as $m) {
            $saldo += $m->qty;
            $m->saldo = $saldo;
        }

        return view('stocks.card', [
            'product'   => $product,
            'movements' => $movements,
            'from'      => $request->from,
            'to'        => $request->to,
        ]);
    }

    /* =========================
     * EXPORT EXCEL
     * ========================= */
    public function exportExcel()
    {
        return Excel::download(
            new StockExport,
            'stok-barang.xlsx'
        );
    }

    public function exportCardExcel(Request $request, Product $product)
    {
        return Excel::download(
            new StockCardExport(
                $product,
                $request->from,
                $request->to
            ),
            'kartu-stok-' . $product->sku . '.xlsx'
        );
    }
}
