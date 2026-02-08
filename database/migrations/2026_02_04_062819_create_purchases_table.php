<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('purchases', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number', 50)->unique();
            $table->date('date');
            $table->foreignId('supplier_id')->constrained()->restrictOnDelete();
            $table->enum('payment_type', ['CASH', 'CREDIT']);
            $table->decimal('subtotal', 16, 2)->default(0);
            $table->decimal('discount', 16, 2)->default(0);
            $table->decimal('tax', 16, 2)->default(0);
            $table->decimal('grand_total', 16, 2)->default(0);
            $table->enum('status', ['OPEN', 'PARTIAL', 'PAID'])->default('OPEN');
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();
        });

        Schema::create('purchase_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('purchase_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->constrained()->restrictOnDelete();
            $table->decimal('qty', 16, 4);
            $table->decimal('price', 16, 2);
            $table->decimal('subtotal', 16, 2);
        });

        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->enum('reference_type', ['SALE', 'PURCHASE']);
            $table->unsignedBigInteger('reference_id');
            $table->foreignId('payment_method_id')->constrained();
            $table->decimal('amount', 16, 2);
            $table->date('date');
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();
        });

        Schema::create('supplier_payables', function (Blueprint $table) {
            $table->id();
            $table->foreignId('purchase_id')->constrained()->cascadeOnDelete();
            $table->foreignId('supplier_id')->constrained();
            $table->decimal('total', 16, 2);
            $table->decimal('paid', 16, 2)->default(0);
            $table->decimal('balance', 16, 2);
            $table->enum('status', ['OPEN', 'PAID'])->default('OPEN');
            $table->timestamps();
        });

        Schema::create('stock_movements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained();
            $table->decimal('qty', 16, 4);
            $table->enum('type', ['IN', 'OUT', 'ADJUST']);
            $table->enum('source_type', ['SALE', 'PURCHASE', 'ADJUSTMENT']);
            $table->unsignedBigInteger('source_id');
            $table->string('note')->nullable();
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchases');
        Schema::dropIfExists('purchase_items');
        Schema::dropIfExists('payments');
        Schema::dropIfExists('supplier_payables');
        Schema::dropIfExists('stock_movements');
    }
};
