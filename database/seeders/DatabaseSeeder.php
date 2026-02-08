<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            PermissionSeeder::class,
            RoleSeeder::class,
            UserSeeder::class, // superadmin user
            PaymentMethodPermissionSeeder::class,
            SupplierPaymentPermissionSeeder::class,
            ReportsPermissionSeeder::class,
            AccountsPermissionSeeder::class,
            JournalAndProfitLossPermissionSeeder::class,
            AccountsTableSeeder::class,
            CustomerPaymentPermissionSeeder::class,
            JournalPermissionSeeder::class,
            ExpensePermissionSeeder::class,
            ProfitLossPermissionSeeder::class,
        ]);
    }
}
