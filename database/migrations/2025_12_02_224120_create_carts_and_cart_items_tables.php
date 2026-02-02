<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // carts
        Schema::create('carts', function (Blueprint $table) {
            $table->bigIncrements('id'); // BIGSERIAL PK
            $table->unsignedBigInteger('customer_id')->index();
            $table->unsignedBigInteger('branch_id')->index();
            $table->timestampsTz(); // created_at, updated_at TIMESTAMPTZ
            $table->softDeletesTz(); // deleted_at TIMESTAMPTZ NULLABLE

            // foreign keys (as requested)
            $table->foreign('customer_id')
                ->references('id')->on('customers')
                ->onUpdate('cascade');
            $table->foreign('branch_id')
                ->references('id')->on('branches')
                ->onUpdate('cascade');
        });

        // cart_items
        Schema::create('cart_items', function (Blueprint $table) {
            $table->bigIncrements('id'); // BIGSERIAL PK
            $table->unsignedBigInteger('cart_id')->index();
            $table->unsignedBigInteger('product_id')->index();
            $table->unsignedBigInteger('variant_id')->index();
            $table->integer('quantity');
            $table->bigInteger('price_cents'); // snapshot price saat ditambahkan
            $table->timestampsTz(); // created_at, updated_at TIMESTAMPTZ
            $table->softDeletesTz(); // deleted_at TIMESTAMPTZ NULLABLE

            // unique per cart_id + variant_id
            $table->unique(['cart_id', 'variant_id'], 'cart_variant_unique');

            // foreign keys
            $table->foreign('cart_id')
                ->references('id')->on('carts')
                ->onDelete('cascade') // ON DELETE CASCADE
                ->onUpdate('cascade');

            $table->foreign('product_id')
                ->references('id')->on('products')
                ->onUpdate('cascade');

            $table->foreign('variant_id')
                ->references('id')->on('product_variants')
                ->onUpdate('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('cart_items', function (Blueprint $table) {
            // drop foreign keys (make sure names exist; using Laravel default naming)
            $table->dropForeign(['cart_id']);
            $table->dropForeign(['product_id']);
            $table->dropForeign(['variant_id']);
            $table->dropUnique('cart_variant_unique');
        });

        Schema::dropIfExists('cart_items');

        Schema::table('carts', function (Blueprint $table) {
            $table->dropForeign(['customer_id']);
            $table->dropForeign(['branch_id']);
        });

        Schema::dropIfExists('carts');
    }
};
