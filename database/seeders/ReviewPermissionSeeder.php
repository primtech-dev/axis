<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class ReviewPermissionSeeder extends Seeder
{
    public function run(): void
    {
        // pastikan guard konsisten
        $guard = 'web';

        $permissions = [
            'reviews.view',
            'reviews.manage', // status, soft delete, remove image
        ];

        $permissionModels = [];

        foreach ($permissions as $permission) {
            $permissionModels[] = Permission::firstOrCreate([
                'name'       => $permission,
                'guard_name' => $guard,
            ]);
        }

        // ambil role superadmin
        $superAdmin = Role::where('name', 'superadmin')
            ->where('guard_name', $guard)
            ->first();

        if ($superAdmin) {
            // assign semua permission review ke superadmin
            $superAdmin->givePermissionTo($permissionModels);
        }
    }
}
