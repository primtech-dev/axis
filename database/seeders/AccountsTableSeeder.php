<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Account;

class AccountsTableSeeder extends Seeder
{
    public function run(): void
    {
        $accounts = [
            [
                'code' => '1101',
                'name' => 'Kas',
                'type' => 'ASSET',
                'is_active' => true,
            ],
            [
                'code' => '1102',
                'name' => 'Bank',
                'type' => 'ASSET',
                'is_active' => true,
            ],
            [
                'code' => '1103',
                'name' => 'Piutang Usaha',
                'type' => 'ASSET',
                'is_active' => true,
            ],
            [
                'code' => '2101',
                'name' => 'Utang Usaha',
                'type' => 'LIABILITY',
                'is_active' => true,
            ],
            [
                'code' => '4101',
                'name' => 'Penjualan',
                'type' => 'INCOME',
                'is_active' => true,
            ],
            [
                'code' => '5101',
                'name' => 'Biaya Pembelian',
                'type' => 'EXPENSE',
                'is_active' => true,
            ],
        ];

        foreach ($accounts as $account) {
            Account::updateOrCreate(
                ['code' => $account['code']], // unique key
                $account
            );
        }
    }
}
