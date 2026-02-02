<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_variants', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('product_id')->unsigned();
            $table->text('sku')->unique();
            $table->text('variant_name');
            $table->bigInteger('price_cents')->nullable()->default(0)->index();
            $table->bigInteger('retail_price_cents')->nullable()->default(0);
            $table->bigInteger('cost_cents')->nullable()->default(0);
            $table->decimal('length', 14, 2)->nullable();
            $table->decimal('width', 14, 2)->nullable();
            $table->decimal('height', 14, 2)->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('is_sellable')->default(true);
            $table->timestampsTz();
            $table->softDeletesTz();

            $table->index('product_id');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
        });

        // Note: If you want sku to be optional (nullable) change unique to index + application-level check.
    }

    public function down(): void
    {
        Schema::dropIfExists('product_variants');
    }
};
