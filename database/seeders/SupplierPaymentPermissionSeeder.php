<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class SupplierPaymentPermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            [
                'name' => 'supplier_payments.view',
                'display_name' => 'Supplier Payments View',
            ],
            [
                'name' => 'supplier_payments.create',
                'display_name' => 'Supplier Payments Create',
            ],
            [
                'name' => 'supplier_payments.update',
                'display_name' => 'Supplier Payments Update',
            ],
            [
                'name' => 'supplier_payments.delete',
                'display_name' => 'Supplier Payments Delete',
            ],
        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate(
                ['name' => $perm['name']],
                [
                    'guard_name'   => 'web',
                    'display_name'=> $perm['display_name'],
                ]
            );
        }

        // Assign ke superadmin
        $superAdmin = Role::where('name', 'superadmin')->first();

        if ($superAdmin) {
            $superAdmin->givePermissionTo(
                collect($permissions)->pluck('name')->toArray()
            );
        }
    }
}
