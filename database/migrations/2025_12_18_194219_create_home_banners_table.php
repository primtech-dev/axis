<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('home_banners', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text('code')->unique();
            $table->text('title')->nullable();
            $table->text('image_path');
            $table->text('image_mobile_path')->nullable();
            $table->text('link_url')->nullable();
            $table->integer('sort_order')->default(0)->index();
            $table->boolean('is_active')->default(true);
            $table->timestampTz('start_at')->nullable();
            $table->timestampTz('end_at')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestampsTz();
            $table->softDeletesTz();

            $table->foreign('created_by')
                ->references('id')
                ->on('users')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('home_banners');
    }
};
