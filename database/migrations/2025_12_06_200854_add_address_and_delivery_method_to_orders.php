<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAddressAndDeliveryMethodToOrders extends Migration
{
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {

            // 1) address_id (nullable)
            $table->unsignedBigInteger('address_id')
                ->nullable();

            $table->foreign('address_id')
                ->references('id')
                ->on('addresses')
                ->onDelete('set null');

            // 2) delivery_method ("delivery" / "pickup")
            $table->string('delivery_method', 50)
                ->nullable();

            // Index recommended
            $table->index('address_id');
            $table->index('delivery_method');
        });
    }

    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['address_id']);
            $table->dropIndex(['address_id']);
            $table->dropColumn('address_id');

            $table->dropIndex(['delivery_method']);
            $table->dropColumn('delivery_method');
        });
    }
}
