<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;
use App\Models\User;

class JournalAndProfitLossPermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Reset permission cache
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            // Journals
            'journals.view',
            'journals.create',
            'journals.delete',

            // Reports
            'reports.profit-loss',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'web',
            ]);
        }

        // Assign ke role superadmin
        $superAdminRole = Role::where('name', 'superadmin')->first();

        if ($superAdminRole) {
            $superAdminRole->givePermissionTo($permissions);
        }

        // Assign ke semua user yang punya role superadmin
        $superAdminUsers = User::whereHas('roles', function ($q) {
            $q->where('name', 'superadmin');
        })->get();

        foreach ($superAdminUsers as $user) {
            $user->givePermissionTo($permissions);
        }
    }
}
