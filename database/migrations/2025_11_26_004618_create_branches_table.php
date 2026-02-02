<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('branches', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text('code')->unique();
            $table->text('name');
            $table->text('address')->nullable();
            $table->text('city')->nullable()->index();
            $table->text('province')->nullable();
            $table->text('phone')->nullable();
            $table->double('latitude')->nullable();
            $table->double('longitude')->nullable();
            $table->unsignedBigInteger('manager_user_id')->nullable()->index();
            $table->boolean('is_active')->default(true);
            $table->timestampsTz();
            $table->softDeletesTz();

            // FK to admin_users if table exists (nullable)
            if (Schema::hasTable('users')) {
                $table->foreign('manager_user_id')->references('id')->on('users')->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('branches');
    }
};
