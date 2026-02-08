<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class ProfitLossPermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cache permission
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            'profit_loss.view',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'web',
            ]);
        }

        // Assign ke role superadmin SAJA
        $superAdminRole = Role::where('name', 'superadmin')->first();

        if ($superAdminRole) {
            $superAdminRole->givePermissionTo($permissions);
        }
    }
}
