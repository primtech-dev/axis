<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStockMovementsTable extends Migration
{
    public function up()
    {
        Schema::create('stock_movements', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('variant_id')->index();
            $table->unsignedBigInteger('branch_id')->index();
            $table->integer('change');
            $table->integer('resulting_on_hand')->nullable();
            $table->string('reason', 64)->nullable()->index(); // order_reserved, receipt, restock, adjustment, etc
            $table->string('reference_type')->nullable();
            $table->uuid('reference_id')->nullable()->index();
            $table->unsignedBigInteger('performed_by')->nullable();
            $table->jsonb('metadata')->nullable();
            $table->timestampTz('created_at')->useCurrent();
            $table->foreign('variant_id')->references('id')->on('product_variants')->onDelete('cascade');
            $table->foreign('branch_id')->references('id')->on('branches')->onDelete('cascade');
            $table->foreign('performed_by')->references('id')->on('users')->onDelete('set null');
            $table->index(['variant_id','branch_id','created_at']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('stock_movements');
    }
}
