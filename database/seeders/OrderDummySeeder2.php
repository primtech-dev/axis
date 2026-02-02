<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\Order;
use App\Models\OrderItem;

class OrderDummySeeder2 extends Seeder
{
    public function run()
    {
        // ===========================================================
        // ORDER 1 — Pending
        // ===========================================================
        $order1 = Order::create([
            'public_id' => Str::uuid(),
            'order_no' => 'ORD' . strtoupper(Str::random(8)),
            'customer_id' => 1,
            'branch_id' => 1,
            'address_id' => 1,
            'status' => 'pending',
            'currency' => 'IDR',

            'subtotal_before_discount' => 100000,
            'discount_total' => 0,
            'subtotal_after_discount' => 100000,
            'shipping_cost' => 15000,
            'tax_total' => 10000,
            'total_amount' => 125000,

            'payment_status' => 'pending',
        ]);

        OrderItem::create([
            'order_id' => $order1->id,
            'product_id' => 10,
            'variant_id' => 11,
            'sku' => 'SKU-10-11',
            'product_name' => 'Produk 10 - Varian 11',
            'unit_price' => 100000,
            'line_quantity' => 1,
            'line_total_before_discount' => 100000,
            'line_discount' => 0,
            'line_total_after_discount' => 100000,
            'line_tax' => 10000,
            'line_total' => 110000,
            'unit_id' => null,
        ]);



        // ===========================================================
        // ORDER 2 — Paid / Processing
        // ===========================================================
        $order2 = Order::create([
            'public_id' => Str::uuid(),
            'order_no' => 'ORD' . strtoupper(Str::random(8)),
            'customer_id' => 1,
            'branch_id' => 1,
            'address_id' => 1,
            'status' => 'processing',
            'currency' => 'IDR',

            'subtotal_before_discount' => 200000,
            'discount_total' => 20000,
            'subtotal_after_discount' => 180000,
            'shipping_cost' => 18000,
            'tax_total' => 15000,
            'total_amount' => 213000,

            'payment_status' => 'paid',
        ]);

        OrderItem::create([
            'order_id' => $order2->id,
            'product_id' => 10,
            'variant_id' => 11,
            'sku' => 'SKU-10-11',
            'product_name' => 'Produk 10 - Varian 11',
            'unit_price' => 100000,
            'line_quantity' => 2,
            'line_total_before_discount' => 200000,
            'line_discount' => 20000,
            'line_total_after_discount' => 180000,
            'line_tax' => 15000,
            'line_total' => 195000,
            'unit_id' => null,
        ]);



        // ===========================================================
        // ORDER 3 — Shipped
        // ===========================================================
        $order3 = Order::create([
            'public_id' => Str::uuid(),
            'order_no' => 'ORD' . strtoupper(Str::random(8)),
            'customer_id' => 1,
            'branch_id' => 1,
            'address_id' => 1,
            'status' => 'shipped',
            'currency' => 'IDR',

            'subtotal_before_discount' => 300000,
            'discount_total' => 0,
            'subtotal_after_discount' => 300000,
            'shipping_cost' => 20000,
            'tax_total' => 30000,
            'total_amount' => 350000,

            'payment_status' => 'paid',
        ]);

        OrderItem::create([
            'order_id' => $order3->id,
            'product_id' => 10,
            'variant_id' => 11,
            'sku' => 'SKU-10-11',
            'product_name' => 'Produk 10 - Varian 11',
            'unit_price' => 100000,
            'line_quantity' => 3,
            'line_total_before_discount' => 300000,
            'line_discount' => 0,
            'line_total_after_discount' => 300000,
            'line_tax' => 30000,
            'line_total' => 330000,
            'unit_id' => null,
        ]);

        $this->command->info("Dummy orders dengan product_id=10, variant_id=11 berhasil dibuat.");
    }
}
