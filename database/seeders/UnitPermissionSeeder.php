<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class UnitPermissionSeeder extends Seeder
{
    public function run()
    {
        $permissions = [
            'units.view',
            'units.create',
            'units.update',
            'units.delete',
        ];

        // If using spatie/laravel-permission package
        if (class_exists(\Spatie\Permission\Models\Permission::class) && class_exists(\Spatie\Permission\Models\Role::class)) {
            foreach ($permissions as $p) {
                \Spatie\Permission\Models\Permission::firstOrCreate(['name' => $p, 'guard_name' => 'web']);
            }

            // Ensure superadmin role exists
            $role = \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'superadmin', 'guard_name' => 'web']);

            // Give all units.* permissions to superadmin
            $role->givePermissionTo($permissions);

            $this->command->info('permissions units.* created and assigned to role superadmin (spatie).');
            return;
        }

        // Fallback: manual DB insertion (assumes tables: permissions, roles, role_has_permissions)
        // Insert permissions
        foreach ($permissions as $p) {
            DB::table('permissions')->insertOrIgnore([
                'name' => $p,
                'guard_name' => 'web',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Ensure role superadmin exists
        $super = DB::table('roles')->where('name', 'superadmin')->first();
        if (!$super) {
            $roleId = DB::table('roles')->insertGetId([
                'name' => 'superadmin',
                'guard_name' => 'web',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } else {
            $roleId = $super->id;
        }

        // link permissions to role (role_has_permissions)
        $permissionRows = DB::table('permissions')->whereIn('name', $permissions)->get();
        foreach ($permissionRows as $perm) {
            DB::table('role_has_permissions')->insertOrIgnore([
                'permission_id' => $perm->id,
                'role_id' => $roleId,
            ]);
        }

        $this->command->info('permissions units.* created and assigned to role superadmin (db fallback).');
    }
}
