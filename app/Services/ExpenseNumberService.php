<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class ExpenseNumberService
{
    public static function generate(): string
    {
        return DB::transaction(function () {

            $period = now()->format('Y-m');

            $row = DB::table('expense_sequences')
                ->where('period', $period)
                ->lockForUpdate()
                ->first();

            if (! $row) {
                DB::table('expense_sequences')->insert([
                    'period' => $period,
                    'last_number' => 1,
                ]);
                $number = 1;
            } else {
                $number = $row->last_number + 1;
                DB::table('expense_sequences')
                    ->where('period', $period)
                    ->update(['last_number' => $number]);
            }

            return sprintf(
                'EX-%s-%03d',
                now()->format('Y-m'),
                $number
            );
        });
    }
}
