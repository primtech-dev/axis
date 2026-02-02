<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Cabang tempat user beroperasi; NULL = pusat / multi / belum di-assign
            $table->foreignId('branch_id')->nullable()->constrained('branches')->nullOnDelete()->after('id');

            // Bubble flags
            $table->boolean('is_superadmin')->default(false)->index();
            $table->boolean('is_active')->default(true)->index();

            // Opsional, bermanfaat untuk operasional
            $table->string('phone')->nullable();
            $table->string('address')->nullable();
            $table->timestamp('last_login_at')->nullable();
            $table->json('meta')->nullable();

            // Index helpful
            $table->index(['branch_id', 'is_superadmin']);
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['branch_id', 'is_superadmin']);
            $table->dropColumn(['meta', 'last_login_at', 'phone', 'address','is_active', 'is_superadmin']);
            $table->dropConstrainedForeignId('branch_id');
        });
    }
};
