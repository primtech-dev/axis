<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInventoryTable extends Migration
{
    public function up()
    {
        Schema::create('inventory', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('variant_id')->index();
            $table->unsignedBigInteger('branch_id')->index();
            $table->integer('on_hand')->default(0);
            $table->integer('available')->default(0)->nullable();
            $table->integer('reserved')->default(0);
            $table->integer('incoming')->default(0);
            $table->integer('safety_stock')->default(0);
            $table->timestampsTz();
            $table->softDeletesTz();
            $table->unique(['variant_id','branch_id']);
            $table->foreign('variant_id')->references('id')->on('product_variants')->onDelete('cascade');
            $table->foreign('branch_id')->references('id')->on('branches')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('inventory');
    }
}
