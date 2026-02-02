<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->uuid('public_id')->unique();

            $table->foreignId('customer_id')
                ->constrained('customers', 'id')
                ->cascadeOnDelete();

            $table->foreignId('order_id')
                ->nullable()
                ->references('id')->on('orders')
                ->nullOnDelete();

            $table->foreignId('order_item_id')
                ->nullable()
                ->references('id')->on('order_items')
                ->nullOnDelete();

            $table->foreignId('product_id')
                ->references('id')->on('products')
                ->cascadeOnDelete();

            $table->foreignId('variant_id')
                ->nullable()
                ->references('id')->on('product_variants')
                ->nullOnDelete();

            $table->unsignedSmallInteger('rating')->index();
            $table->text('title')->nullable();
            $table->text('body');
            $table->boolean('is_verified_purchase')->default(false);
            $table->string('status')->default('pending')->index();

            $table->timestamps();
            $table->softDeletes();
        });

    }

    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
