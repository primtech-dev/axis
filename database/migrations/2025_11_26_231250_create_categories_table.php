<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategoriesTable extends Migration
{
    public function up()
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text('name');
            $table->text('slug')->unique();
            $table->unsignedBigInteger('parent_id')->nullable()->index();
            $table->integer('position')->default(0);
            $table->boolean('is_active')->default(true)->index();
            $table->timestampsTz();
            $table->softDeletesTz();

            $table->foreign('parent_id')->references('id')->on('categories')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('categories', function ($table) {
            $table->dropForeign(['parent_id']);
        });
        Schema::dropIfExists('categories');
    }
}
