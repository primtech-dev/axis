<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class ReviewSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('reviews')->insert([
            'public_id' => Str::uuid(),
            'customer_id' => 1,
            'order_id' => 6,
            'order_item_id' => 6,
            'product_id' => 10,
            'variant_id' => 11,
            'rating' => 5,
            'title' => 'Mantap',
            'body' => 'Barang kualitas bagus',
            'is_verified_purchase' => true,
            'status' => 'publish',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
    }
}
