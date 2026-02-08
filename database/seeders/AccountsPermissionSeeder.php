<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Models\User;
use Spatie\Permission\PermissionRegistrar;

class AccountsPermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cache permission
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            'accounts.view',
            'accounts.create',
            'accounts.update',
            'accounts.delete',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'web',
            ]);
        }

        // Ambil role superadmin
        $superAdminRole = Role::where('name', 'superadmin')->first();

        if ($superAdminRole) {
            $superAdminRole->givePermissionTo($permissions);
        }

        // Assign ke user superadmin (opsional tapi sesuai request kamu)
        $superAdminUser = User::whereHas('roles', function ($q) {
            $q->where('name', 'superadmin');
        })->get();

        foreach ($superAdminUser as $user) {
            $user->givePermissionTo($permissions);
        }
    }
}
