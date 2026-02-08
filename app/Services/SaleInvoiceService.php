<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class SaleInvoiceService
{
    public static function generate(): string
    {
        return DB::transaction(function () {

            $period = now()->format('Y-m');

            $row = DB::table('sales_sequences')
                ->where('period', $period)
                ->lockForUpdate()
                ->first();

            if (!$row) {
                DB::table('sales_sequences')->insert([
                    'period' => $period,
                    'last_number' => 1,
                ]);
                $number = 1;
            } else {
                $number = $row->last_number + 1;
                DB::table('sales_sequences')
                    ->where('period', $period)
                    ->update(['last_number' => $number]);
            }

            return 'SO-' . now()->format('Y-m') . '-' . str_pad($number, 3, '0', STR_PAD_LEFT);
        });
    }
}
