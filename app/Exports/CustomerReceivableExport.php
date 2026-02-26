<?php

namespace App\Exports;

use App\Models\CustomerReceivable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Http\Request;

class CustomerReceivableExport implements FromCollection, WithHeadings
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function collection()
    {
        $query = CustomerReceivable::with(['customer', 'sale'])
            ->where('balance', '>', 0);

        if ($this->request->start_date && $this->request->end_date) {
            $query->whereHas('sale', function ($q) {
                $q->whereBetween('date', [
                    $this->request->start_date,
                    $this->request->end_date
                ]);
            });
        }

        if ($this->request->customer_ids && count($this->request->customer_ids)) {
            $query->whereIn('customer_id', $this->request->customer_ids);
        }

        $data = $query->get();

        $rows = $data->map(function ($r) {
            return [
                $r->customer->name ?? '-',
                $r->sale->invoice_number ?? '-',
                $r->total,
                $r->paid,
                $r->balance,
                optional($r->sale)->due_date,
            ];
        });

        // Tambahkan total di akhir
        $rows->push([
            '',
            'TOTAL',
            $data->sum('total'),
            $data->sum('paid'),
            $data->sum('balance'),
            '',
        ]);

        return $rows;
    }

    public function headings(): array
    {
        return [
            'Customer',
            'Nomor Invoice',
            'Total Transaksi',
            'Terbayar',
            'Sisa Piutang',
            'Jatuh Tempo',
        ];
    }
}
