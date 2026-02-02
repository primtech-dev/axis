<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('product_variants', function (Blueprint $table) {
            $table->dropColumn([
                'price_cents',
                'retail_price_cents',
                'cost_cents',
            ]);
        });
    }

    public function down(): void
    {
        Schema::table('product_variants', function (Blueprint $table) {
            $table->bigInteger('price_cents')->nullable()->default(0)->index();
            $table->bigInteger('retail_price_cents')->nullable()->default(0);
            $table->bigInteger('cost_cents')->nullable()->default(0);
        });
    }
};
