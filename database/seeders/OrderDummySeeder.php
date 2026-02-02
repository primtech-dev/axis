<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\Order;
use App\Models\OrderItem;

class OrderDummySeeder extends Seeder
{
    public function run()
    {
        // ----------------------------
        // Order 1 - Pending
        // ----------------------------
        $order1 = Order::create([
            'public_id' => Str::uuid(),
            'order_no' => 'ORD' . strtoupper(Str::random(8)),
            'customer_id' => 1,
            'branch_id' => 1,
            'status' => 'pending',
            'currency' => 'IDR',
            'subtotal_before_discount' => 150000,
            'discount_total' => 0,
            'subtotal_after_discount' => 150000,
            'shipping_cost' => 20000,
            'tax_total' => 15000,
            'total_amount' => 185000,
            'payment_status' => 'pending',
        ]);

        OrderItem::create([
            'order_id' => $order1->id,
            'product_id' => 7,
            'variant_id' => 6,
            'sku' => 'SKU-7-6',
            'product_name' => 'Produk ID 7 - Varian 6',
            'unit_price' => 75000,
            'line_quantity' => 2,
            'line_total_before_discount' => 150000,
            'line_discount' => 0,
            'line_total_after_discount' => 150000,
            'line_tax' => 15000,
            'line_total' => 165000,
            'unit_id' => null,
        ]);


        // ----------------------------
        // Order 2 - Processing
        // ----------------------------
        $order2 = Order::create([
            'public_id' => Str::uuid(),
            'order_no' => 'ORD' . strtoupper(Str::random(8)),
            'customer_id' => 1,
            'branch_id' => 1,
            'status' => 'processing',
            'currency' => 'IDR',
            'subtotal_before_discount' => 225000,
            'discount_total' => 25000,
            'subtotal_after_discount' => 200000,
            'shipping_cost' => 18000,
            'tax_total' => 20000,
            'total_amount' => 238000,
            'payment_status' => 'paid',
        ]);

        OrderItem::create([
            'order_id' => $order2->id,
            'product_id' => 7,
            'variant_id' => 7,
            'sku' => 'SKU-7-7',
            'product_name' => 'Produk ID 7 - Varian 7',
            'unit_price' => 75000,
            'line_quantity' => 3,
            'line_total_before_discount' => 225000,
            'line_discount' => 25000,
            'line_total_after_discount' => 200000,
            'line_tax' => 20000,
            'line_total' => 220000,
            'unit_id' => null,
        ]);


        // ----------------------------
        // Order 3 - Shipped
        // ----------------------------
        $order3 = Order::create([
            'public_id' => Str::uuid(),
            'order_no' => 'ORD' . strtoupper(Str::random(8)),
            'customer_id' => 1,
            'branch_id' => 1,
            'status' => 'shipped',
            'currency' => 'IDR',
            'subtotal_before_discount' => 300000,
            'discount_total' => 0,
            'subtotal_after_discount' => 300000,
            'shipping_cost' => 25000,
            'tax_total' => 30000,
            'total_amount' => 355000,
            'payment_status' => 'paid',
        ]);

        OrderItem::create([
            'order_id' => $order3->id,
            'product_id' => 7,
            'variant_id' => 6,
            'sku' => 'SKU-7-6',
            'product_name' => 'Produk ID 7 - Varian 6',
            'unit_price' => 100000,
            'line_quantity' => 3,
            'line_total_before_discount' => 300000,
            'line_discount' => 0,
            'line_total_after_discount' => 300000,
            'line_tax' => 30000,
            'line_total' => 330000,
            'unit_id' => null,
        ]);

        $this->command->info("Dummy orders seeded successfully.");
    }
}
