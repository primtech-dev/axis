<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\DB;

class HomeBannerPermissionSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {

            $permissions = [
                'home_banners.view',
                'home_banners.manage',
            ];

            // Buat permission jika belum ada
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
        });
    }
}
