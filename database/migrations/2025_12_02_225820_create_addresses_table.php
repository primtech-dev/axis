<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('addresses', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('customer_id')->index();

            $table->string('label');
            $table->text('street');
            $table->string('city');
            $table->string('province');

            // lokasi
            $table->double('latitude')->nullable();
            $table->double('longitude')->nullable();

            $table->string('postal_code');
            $table->string('country')->default('ID');
            $table->string('phone', 20)->nullable();

            $table->boolean('is_default')->default(false);

            // timestamps & soft deletes (TIMESTAMPTZ)
            $table->timestampsTz();
            $table->softDeletesTz();

            // Foreign key → customers.id
            $table->foreign('customer_id')
                ->references('id')->on('customers')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('addresses', function (Blueprint $table) {
            $table->dropForeign(['customer_id']);
        });

        Schema::dropIfExists('addresses');
    }
};
