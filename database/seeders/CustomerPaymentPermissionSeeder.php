<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CustomerPaymentPermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil role superadmin
        $role = DB::table('roles')
            ->where('name', 'superadmin')
            ->first();

        if (! $role) {
            return;
        }

        // Insert permission jika belum ada
        $permissionId = DB::table('permissions')->where('name', 'customer_payments.create')->value('id');

        if (! $permissionId) {
            $permissionId = DB::table('permissions')->insertGetId([
                'name'       => 'customer_payments.create',
                'guard_name' => 'web',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Assign ke role superadmin (tanpa model / hasPermission)
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
    }
}
