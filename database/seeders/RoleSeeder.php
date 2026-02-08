<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        // =========================
        // ROLES
        // =========================
        $superadmin = Role::firstOrCreate([
            'name' => 'superadmin',
            'guard_name' => 'web',
        ]);

        $inventory = Role::firstOrCreate([
            'name' => 'inventory',
            'guard_name' => 'web',
        ]);

        $kasir = Role::firstOrCreate([
            'name' => 'kasir',
            'guard_name' => 'web',
        ]);

        // =========================
        // SUPERADMIN => ALL ACCESS
        // =========================
        $superadmin->syncPermissions(Permission::all());

        // =========================
        // INVENTORY ROLE
        // =========================
        $inventory->syncPermissions(
            Permission::whereIn('name', [

                // PRODUCTS
                'products.view',
                'products.create',
                'products.update',
                'products.delete',

                // CATEGORIES
                'categories.view',
                'categories.create',
                'categories.update',
                'categories.delete',

                // UNITS
                'units.view',
                'units.create',
                'units.update',
                'units.delete',

                // SUPPLIERS
                'suppliers.view',
                'suppliers.create',
                'suppliers.update',
                'suppliers.delete',

                // PURCHASING
                'purchases.view',
                'purchases.create',
                'purchases.update',
                'purchases.pay',

                // STOCK
                'stock.view',
                'stock.adjust',

            ])->get()
        );

        // =========================
        // KASIR ROLE
        // =========================
        $kasir->syncPermissions(
            Permission::whereIn('name', [

                // SALES
                'sales.view',
                'sales.create',
                'sales.update',
                'sales.pay',

                // CUSTOMERS
                'customers.view',
                'customers.create',
                'customers.update',

            ])->get()
        );
    }
}
