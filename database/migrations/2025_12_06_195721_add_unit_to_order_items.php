<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUnitToOrderItems extends Migration
{
    public function up()
    {
        Schema::table('order_items', function (Blueprint $table) {

            // Snapshot unit pada saat order dibuat.
            // Tidak boleh cascade delete karena ini arsip transaksi.
            $table->unsignedBigInteger('unit_id')
                ->nullable();

            $table->foreign('unit_id')
                ->references('id')
                ->on('units')
                ->onDelete('set null');  // menjaga integrity history

            // Index untuk laporan cepat
            $table->index('unit_id');
        });
    }

    public function down()
    {
        Schema::table('order_items', function (Blueprint $table) {
            $table->dropForeign(['unit_id']);
            $table->dropIndex(['unit_id']);
            $table->dropColumn('unit_id');
        });
    }
}
