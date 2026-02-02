<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUnitsTable extends Migration
{
    public function up()
    {
        Schema::create('units', function (Blueprint $table) {
            $table->bigIncrements('id');
            // kode singkat untuk unit (mis. 'pcs', 'kg', 'm', 'cm')
            $table->string('code', 32)->unique();
            // nama lengkap unit (mis. 'Piece', 'Kilogram', 'Meter')
            $table->string('name');
            $table->timestampsTz(); // created_at, updated_at TIMESTAMPTZ
            $table->softDeletesTz(); // deleted_at TIMESTAMPTZ nullable
        });
    }

    public function down()
    {
        Schema::dropIfExists('units');
    }
}
