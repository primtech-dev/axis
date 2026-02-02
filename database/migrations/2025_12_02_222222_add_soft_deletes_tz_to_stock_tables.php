<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('stock_receipts', function (Blueprint $table) {
            $table->softDeletesTz(); // deleted_at with timezone
        });

        Schema::table('stock_receipt_items', function (Blueprint $table) {
            $table->softDeletesTz();
        });

        Schema::table('stock_movements', function (Blueprint $table) {
            $table->softDeletesTz();
        });
    }

    public function down(): void
    {
        Schema::table('stock_receipts', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('stock_receipt_items', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('stock_movements', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
};
