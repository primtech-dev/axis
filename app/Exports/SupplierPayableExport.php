<?php

namespace App\Exports;

use App\Models\SupplierPayable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class SupplierPayableExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return SupplierPayable::with(['supplier', 'purchase'])
            ->where('balance', '>', 0)
            ->get()
            ->map(function ($p) {
                return [
                    'Supplier'   => $p->supplier->name,
                    'Invoice'    => $p->purchase->invoice_number,
                    'Tanggal'    => $p->purchase->date->format('Y-m-d'),
                    'Total'      => $p->total,
                    'Terbayar'   => $p->paid,
                    'Sisa'       => $p->balance,
                ];
            });
    }

    public function headings(): array
    {
        return [
            'Supplier',
            'Invoice',
            'Tanggal',
            'Total',
            'Terbayar',
            'Sisa Hutang',
        ];
    }
}
