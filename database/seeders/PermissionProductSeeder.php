<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // =============================
        // PERMISSION GROUPING
        // =============================

        $permissions = [
            // Categories
            'categories.view',
            'categories.create',
            'categories.update',
            'categories.delete',

            // Products
            'products.view',
            'products.create',
            'products.update',
            'products.delete',

            // Product Variants
            'product_variants.view',
            'product_variants.create',
            'product_variants.update',
            'product_variants.delete',

            // Product Images
            'product_images.view',
            'product_images.upload',
            'product_images.delete',

            // Product → Category pivot
            'product_categories.assign',
            'product_categories.remove',
        ];

        // =============================
        // CREATE PERMISSIONS
        // =============================

        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm]);
        }

        // =============================
        // ASSIGN TO SUPERADMIN ROLE
        // =============================

        $superadmin = Role::firstOrCreate(['name' => 'superadmin']);

        $superadmin->syncPermissions($permissions); // give all permissions

        // You may include optional logging
        echo "✓ Permission product module seeded & assigned to superadmin.\n";
    }
}
