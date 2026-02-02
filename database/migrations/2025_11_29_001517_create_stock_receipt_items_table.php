<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStockReceiptItemsTable extends Migration
{
    public function up()
    {
        Schema::create('stock_receipt_items', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('receipt_id')->index();
            $table->unsignedBigInteger('variant_id')->index();
            $table->integer('quantity_received')->default(0);
            $table->bigInteger('unit_cost_cents')->nullable();
            $table->timestampsTz();
            $table->foreign('receipt_id')->references('id')->on('stock_receipts')->onDelete('cascade');
            $table->foreign('variant_id')->references('id')->on('product_variants')->onDelete('restrict');
        });
    }

    public function down()
    {
        Schema::dropIfExists('stock_receipt_items');
    }
}
