<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class BranchesPermissionSeeder extends Seeder
{
    public function run()
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $perms = [
            'branches.view',
            'branches.create',
            'branches.update',
            'branches.delete',
        ];

        foreach ($perms as $p) {
            Permission::firstOrCreate(['name' => $p]);
        }

        // contoh assign ke role superadmin & manager (sesuaikan)
        $super = Role::firstOrCreate(['name' => 'superadmin']);
        $super->givePermissionTo($perms);

        // manager cabang biasanya butuh view + update but not delete/create
        $manager = Role::firstOrCreate(['name' => 'manager_cabang']);
        $manager->givePermissionTo(['branches.view', 'branches.update']);
    }
}
