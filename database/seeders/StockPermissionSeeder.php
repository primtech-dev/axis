<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class StockPermissionSeeder extends Seeder
{
    public function run()
    {
        $perms = [
            'stock_receipts.view',
            'stock_receipts.create',
            'stock_receipts.update',
            'stock_receipts.delete',
        ];

        foreach ($perms as $p) {
            Permission::firstOrCreate(['name' => $p]);
        }

        $role = Role::firstOrCreate(['name' => 'superadmin']);
        $role->givePermissionTo($perms);
    }
}
