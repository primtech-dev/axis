<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class ReportsPermissionSeeder extends Seeder
{
    public function run(): void
    {
        // buat permission (aman kalau dijalankan berulang)
        $permission = Permission::firstOrCreate(
            ['name' => 'reports.view'],
            ['guard_name' => 'web']
        );

        // assign ke role superadmin
        $superAdminRole = Role::where('name', 'superadmin')->first();

        if ($superAdminRole) {
            $superAdminRole->givePermissionTo($permission);
        }
    }
}
