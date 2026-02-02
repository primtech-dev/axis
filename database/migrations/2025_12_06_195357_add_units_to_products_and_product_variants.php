<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUnitsToProductsAndProductVariants extends Migration
{
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            // unit default / base untuk produk (nullable supaya tidak memaksa ada unit)
            $table->unsignedBigInteger('unit_id')->nullable();
            $table->foreign('unit_id')
                ->references('id')->on('units')
                ->onDelete('set null');
            // index untuk pencarian cepat
            $table->index('unit_id');
        });

        Schema::table('product_variants', function (Blueprint $table) {
            // unit untuk varian — ini biasanya yang dipakai saat penjualan (nullable)
            $table->unsignedBigInteger('unit_id')->nullable()->after('price');
            $table->foreign('unit_id')
                ->references('id')->on('units')
                ->onDelete('set null');
            $table->index('unit_id');
        });
    }

    public function down()
    {
        Schema::table('product_variants', function (Blueprint $table) {
            // drop fk + column
            $table->dropForeign(['unit_id']);
            $table->dropIndex(['unit_id']);
            $table->dropColumn('unit_id');
        });

        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign(['unit_id']);
            $table->dropIndex(['unit_id']);
            $table->dropColumn('unit_id');
        });
    }
}
