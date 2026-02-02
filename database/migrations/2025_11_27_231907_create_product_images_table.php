<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_images', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('product_id')->unsigned();
            $table->bigInteger('variant_id')->unsigned()->nullable();
            $table->text('url'); // store path (storage path or CDN URL)
            $table->integer('position')->default(0);
            $table->boolean('is_main')->default(false);
            $table->timestampsTz();
            $table->softDeletesTz();

            $table->index('product_id');
            $table->index('variant_id');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->foreign('variant_id')->references('id')->on('product_variants')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_images');
    }
};
