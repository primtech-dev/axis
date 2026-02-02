<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payment_methods', function (Blueprint $table) {

            $table->bigIncrements('id');

            $table->text('code')
                ->unique()
                ->comment('Kode unik method (mis: bank_transfer_bca)');

            $table->text('name')
                ->comment('Nama tampil method');

            $table->text('provider')
                ->comment('Provider/payment gateway (midtrans, xendit, manual)');

            $table->text('type')
                ->comment('Jenis: gateway/bank_transfer/cash_on_delivery/ewallet/qris');

            $table->jsonb('config')
                ->nullable()
                ->comment('Config untuk provider (instruksi, bank account, fee rules)');

            $table->boolean('is_active')
                ->default(true)
                ->index()
                ->comment('Aktif/tidak');

            $table->integer('display_order')
                ->default(0)
                ->comment('Urutan tampil di UI');

            $table->timestampsTz(); // created_at & updated_at TIMESTAMPTZ
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payment_methods');
    }
};
