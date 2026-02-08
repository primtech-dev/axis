<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\PermissionRegistrar;

class JournalPermissionSeeder extends Seeder
{
    public function run(): void
    {
        // 🔥 wajib: reset cache spatie
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        // ambil role superadmin
        $role = DB::table('roles')
            ->where('name', 'superadmin')
            ->first();

        if (! $role) {
            return;
        }

        // permission journals.view
        $permissionName = 'journals.view';

        $permissionId = DB::table('permissions')
            ->where('name', $permissionName)
            ->where('guard_name', 'web')
            ->value('id');

        if (! $permissionId) {
            $permissionId = DB::table('permissions')->insertGetId([
                'name'       => $permissionName,
                'guard_name' => 'web',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // assign ke role superadmin (direct DB)
        $exists = DB::table('role_has_permissions')
            ->where('role_id', $role->id)
            ->where('permission_id', $permissionId)
            ->exists();

        if (! $exists) {
            DB::table('role_has_permissions')->insert([
                'role_id'       => $role->id,
                'permission_id' => $permissionId,
            ]);
        }

        // clear cache lagi biar langsung kebaca middleware
        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }
}
