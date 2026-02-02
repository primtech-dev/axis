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
        Schema::table('payment_methods', function (Blueprint $table) {
            // Tambahkan deleted_at untuk soft delete
            $table->softDeletes(); // sama dengan: $table->timestamp('deleted_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payment_methods', function (Blueprint $table) {
            // Hapus kolom deleted_at ketika rollback
            $table->dropSoftDeletes(); // sama dengan: $table->dropColumn('deleted_at');
        });
    }
};
