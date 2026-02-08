<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PaymentMethodPermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            [
                'name' => 'payment_methods.view',
                'display_name' => 'Payment Methods View',
            ],
            [
                'name' => 'payment_methods.create',
                'display_name' => 'Payment Methods Create',
            ],
            [
                'name' => 'payment_methods.update',
                'display_name' => 'Payment Methods Update',
            ],
            [
                'name' => 'payment_methods.delete',
                'display_name' => 'Payment Methods Delete',
            ],
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(
                [
                    'name' => $permission['name'],
                    'guard_name' => 'web',
                ],
                [
                    'display_name' => $permission['display_name'],
                ]
            );
        }

        // Assign ke role superadmin
        $superAdmin = Role::firstOrCreate([
            'name' => 'superadmin',
            'guard_name' => 'web',
        ]);

        $superAdmin->givePermissionTo(
            collect($permissions)->pluck('name')->toArray()
        );
    }
}
