<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class CategoryPermissionSeeder extends Seeder
{
    public function run()
    {
        $perms = [
            'categories.view',
            'categories.create',
            'categories.update',
            'categories.delete'
        ];

        foreach ($perms as $p) {
            Permission::firstOrCreate(['name' => $p]);
        }

        // contoh assign ke role admin
        $admin = Role::firstOrCreate(['name' => 'superadmin']);
        $admin->givePermissionTo($perms);
    }
}
