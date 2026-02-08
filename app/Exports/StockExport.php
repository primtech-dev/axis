<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class StockExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return DB::table('products as p')
            ->join('units as u', 'u.id', '=', 'p.unit_id')
            ->select(
                'p.sku',
                'p.name',
                'u.name as unit',
                DB::raw('(
                    SELECT COALESCE(SUM(sm.qty), 0)
                    FROM stock_movements sm
                    WHERE sm.product_id = p.id
                ) as stock')
            )
            ->where('p.is_active', 1)
            ->get();
    }

    public function headings(): array
    {
        return [
            'SKU',
            'Nama Barang',
            'Satuan',
            'Stok',
        ];
    }
}
