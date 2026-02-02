<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStockReceiptsTable extends Migration
{
    public function up()
    {
        Schema::create('stock_receipts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->uuid('public_id')->unique()->index();
            $table->unsignedBigInteger('branch_id')->index();
            $table->text('supplier_name')->nullable();
            $table->text('reference_no')->nullable()->index();
            $table->string('status', 32)->default('draft')->index(); // draft|received|cancelled
            $table->integer('total_items')->default(0);
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestampTz('received_at')->nullable()->index();
            $table->text('notes')->nullable();
            $table->jsonb('meta')->nullable();
            $table->timestampsTz();
            // FK constraints (adjust table names if different)
            $table->foreign('branch_id')->references('id')->on('branches')->onDelete('restrict');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::dropIfExists('stock_receipts');
    }
}
