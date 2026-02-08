<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class ExpensePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cache permission
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            'expenses.view',
            'expenses.create',
            'expenses.update',
            'expenses.delete',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'web',
            ]);
        }

        // Assign ke role superadmin SAJA (tanpa model_has_permissions)
        $superAdminRole = Role::where('name', 'superadmin')->first();

        if ($superAdminRole) {
            $superAdminRole->givePermissionTo($permissions);
        }
    }
}
