<?php

namespace App\Exports;

use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class StockCardExport implements FromCollection, WithHeadings
{
    protected Product $product;
    protected ?string $from;
    protected ?string $to;

    public function __construct(Product $product, ?string $from = null, ?string $to = null)
    {
        $this->product = $product;
        $this->from = $from;
        $this->to = $to;
    }

    /**
     * Data export
     */
    public function collection()
    {
        $query = DB::table('stock_movements')
            ->where('product_id', $this->product->id);

        if ($this->from) {
            $query->whereDate('created_at', '>=', $this->from);
        }

        if ($this->to) {
            $query->whereDate('created_at', '<=', $this->to);
        }

        $movements = $query
            ->orderBy('created_at')
            ->get();

        $saldo = 0;

        return $movements->map(function ($m) use (&$saldo) {

            $saldo += $m->qty;

            return [
                'Tanggal'   => $m->created_at,
                'Tipe'      => $m->type,
                'Sumber'    => $m->source_type,
                'Referensi' => $m->source_id,
                'Masuk'     => $m->qty > 0 ? $m->qty : 0,
                'Keluar'    => $m->qty < 0 ? abs($m->qty) : 0,
                'Saldo'     => $saldo,
            ];
        });
    }

    /**
     * Header kolom Excel
     */
    public function headings(): array
    {
        return [
            'Tanggal',
            'Tipe',
            'Sumber',
            'Referensi',
            'Masuk',
            'Keluar',
            'Saldo',
        ];
    }
}
