<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('stock_receipt_items', function (Blueprint $table) {
            $table->dropColumn('unit_cost_cents');
        });
    }

    public function down(): void
    {
        Schema::table('stock_receipt_items', function (Blueprint $table) {
            $table->bigInteger('unit_cost_cents')->nullable();
        });
    }
};
