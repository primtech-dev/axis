<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class CustomerPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            'customers.view',
            'customers.activate',
        ];

        // Create permissions if not exist
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Assign to superadmin
        $role = Role::firstOrCreate(['name' => 'superadmin']);
        $role->givePermissionTo($permissions);

        echo "Customer permissions seeded & assigned to superadmin.\n";
    }
}
