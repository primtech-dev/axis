<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('supplier_payables', function (Blueprint $table) {

            // tambah kolom baru
            $table->enum('reference_type', ['PURCHASE', 'EXPENSE'])
                ->after('id');

            $table->unsignedBigInteger('reference_id')
                ->after('reference_type');

            // index buat performa
            $table->index(['reference_type', 'reference_id']);
        });

        /**
         * MIGRASI DATA LAMA
         * purchase_id -> reference
         */
        DB::table('supplier_payables')->update([
            'reference_type' => 'PURCHASE',
            'reference_id'   => DB::raw('purchase_id'),
        ]);

        Schema::table('supplier_payables', function (Blueprint $table) {
            // hapus foreign key & kolom lama
            $table->dropForeign(['purchase_id']);
            $table->dropColumn('purchase_id');
        });
    }

    public function down(): void
    {
        Schema::table('supplier_payables', function (Blueprint $table) {

            $table->unsignedBigInteger('purchase_id')->nullable();

            $table->foreign('purchase_id')
                ->references('id')
                ->on('purchases')
                ->cascadeOnDelete();
        });

        DB::table('supplier_payables')->update([
            'purchase_id' => DB::raw('reference_id'),
        ]);

        Schema::table('supplier_payables', function (Blueprint $table) {
            $table->dropIndex(['reference_type', 'reference_id']);
            $table->dropColumn(['reference_type', 'reference_id']);
        });
    }
};
