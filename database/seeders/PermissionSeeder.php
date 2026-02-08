<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $permissions = [

            // PRODUCTS
            ['products.view', 'inventory'],
            ['products.create', 'inventory'],
            ['products.update', 'inventory'],
            ['products.delete', 'inventory'],

            // CATEGORIES
            ['categories.view', 'inventory'],
            ['categories.create', 'inventory'],
            ['categories.update', 'inventory'],
            ['categories.delete', 'inventory'],

            // UNITS
            ['units.view', 'inventory'],
            ['units.create', 'inventory'],
            ['units.update', 'inventory'],
            ['units.delete', 'inventory'],

            // SUPPLIERS
            ['suppliers.view', 'inventory'],
            ['suppliers.create', 'inventory'],
            ['suppliers.update', 'inventory'],
            ['suppliers.delete', 'inventory'],

            // CUSTOMERS
            ['customers.view', 'sales'],
            ['customers.create', 'sales'],
            ['customers.update', 'sales'],
            ['customers.delete', 'sales'],

            // PURCHASING
            ['purchases.view', 'purchasing'],
            ['purchases.create', 'purchasing'],
            ['purchases.update', 'purchasing'],
            ['purchases.pay', 'purchasing'],

            // SALES
            ['sales.view', 'sales'],
            ['sales.create', 'sales'],
            ['sales.update', 'sales'],
            ['sales.pay', 'sales'],

            // STOCK
            ['stock.view', 'inventory'],
            ['stock.adjust', 'inventory'],

            // SYSTEM - USERS
            ['users.view', 'system'],
            ['users.create', 'system'],
            ['users.update', 'system'],
            ['users.delete', 'system'],

            // SYSTEM - ROLES
            ['roles.view', 'system'],
            ['roles.create', 'system'],
            ['roles.update', 'system'],
            ['roles.delete', 'system'],

            // SYSTEM - SETTINGS
            ['settings.view', 'system'],
            ['settings.update', 'system'],
        ];

        foreach ($permissions as [$name, $group]) {
            Permission::updateOrCreate(
                ['name' => $name, 'guard_name' => 'web'],
                [
                    'display_name' => ucwords(str_replace('.', ' ', $name)),
                    'group' => $group,
                ]
            );
        }
    }
}
