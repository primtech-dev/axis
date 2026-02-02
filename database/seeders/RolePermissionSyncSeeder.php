<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Models\User;

class RolePermissionSyncSeeder extends Seeder
{
    public function run(): void
    {
        $driver = DB::getDriverName();
        if ($driver === 'mysql') {
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        } elseif ($driver === 'sqlite') {
            DB::statement('PRAGMA foreign_keys = OFF;');
        }

        // clear spatie cache
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // daftar permission default (ubah sesuai kebutuhan)
        $modules = [
            'roles' => ['view', 'create', 'update', 'delete'],
            'permissions' => ['view', 'create', 'update', 'delete'],
            'users' => ['view', 'create', 'update', 'delete'],
            'categories' => ['view', 'create', 'update', 'delete'],
            'products' => ['view', 'create', 'update', 'delete'],
            'customers' => ['view', 'create', 'update', 'delete'],
            'orders' => ['view', 'create', 'update', 'delete'],
            'settings' => ['view', 'update'],
        ];

        $hasDisplay = Schema::hasColumn('permissions', 'display_name');
        $hasGroup = Schema::hasColumn('permissions', 'group');

        foreach ($modules as $group => $actions) {
            foreach ($actions as $action) {
                $name = "{$group}.{$action}";

                $defaults = ['guard_name' => 'web'];
                if ($hasDisplay) {
                    $defaults['display_name'] = $this->humanizePermission($group, $action);
                }
                if ($hasGroup) {
                    $defaults['group'] = ucfirst($group);
                }

                Permission::firstOrCreate(
                    ['name' => $name, 'guard_name' => 'web'],
                    $defaults
                );
            }
        }

        // pastikan role superadmin ada (tanpa dash)
        $roleName = 'superadmin';
        $superAdmin = Role::firstOrCreate(['name' => $roleName, 'guard_name' => 'web']);

        // berikan semua permission yang ada kepada superadmin
        $allPermissions = Permission::all();
        $superAdmin->syncPermissions($allPermissions);

        // opsional: buat user superadmin default jika belum ada
        $defaultEmail = 'superadmin@example.com';
        if (!User::where('email', $defaultEmail)->exists()) {
            $pwd = 'password123'; // ganti segera setelah login
            $user = User::create([
                'name' => 'Super Admin',
                'email' => $defaultEmail,
                'password' => Hash::make($pwd),
            ]);
            $user->assignRole($superAdmin);
            $this->command->info("User superadmin dibuat: {$defaultEmail} / {$pwd}");
        } else {
            $this->command->info("User superadmin dengan email {$defaultEmail} sudah ada — tidak dibuat ulang.");
        }

        // refresh spatie cache
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        if ($driver === 'mysql') {
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        } elseif ($driver === 'sqlite') {
            DB::statement('PRAGMA foreign_keys = ON;');
        }

        $this->command->info('✓ RolePermissionSyncSeeder selesai.');
    }

    private function humanizePermission(string $group, string $action): string
    {
        // contoh: 'users', 'create' -> 'User - Buat'
        $map = [
            'view' => 'Lihat',
            'create' => 'Buat',
            'update' => 'Ubah',
            'delete' => 'Hapus',
        ];

        $groupLabel = str_replace('_', ' ', ucfirst($group));
        $actionLabel = $map[$action] ?? ucfirst($action);

        return "{$groupLabel} — {$actionLabel}";
    }
}
