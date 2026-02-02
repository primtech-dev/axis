<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class OrdersPermissionSeeder extends Seeder
{
    public function run()
    {
        $permissions = [
            'orders.view',      // melihat daftar & detail order
            'orders.manage',    // ubah status, delete, aksi manajemen order
            'orders.ship',      // khusus untuk shipping (opsional)
            'orders.refund',    // refund
        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm]);
        }

        $this->command->info('Order permissions seeded.');
    }
}
