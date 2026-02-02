<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Create super admin user
        $superAdmin = User::firstOrCreate(
            ['email' => 'superadmin@mail.com'],
            [
                'name' => 'Admin',
                'password' => Hash::make('password'),
            ]
        );

        $role = Role::firstOrCreate(['name' => 'superadmin']);
        $superAdmin->syncRoles([$role->name]);

        // \Spatie\Permission\PermissionRegistrar::forgetCachedPermissions();
        app(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();
    }
}
