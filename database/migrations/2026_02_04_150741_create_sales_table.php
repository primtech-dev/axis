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
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number', 50)->unique();
            $table->date('date');

            $table->foreignId('customer_id')
                ->nullable()
                ->constrained('customers');

            $table->enum('payment_type', ['CASH', 'CREDIT']);

            $table->decimal('subtotal', 16, 2);
            $table->decimal('discount', 16, 2)->default(0);
            $table->decimal('tax', 16, 2)->default(0);
            $table->decimal('grand_total', 16, 2);

            $table->enum('status', ['OPEN', 'PARTIAL', 'PAID'])->default('OPEN');

            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};
