<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBinariesCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('binaries_categories', function(Blueprint $tbl)
        {
            $tbl->integer('binary_id')->unsigned();
            $tbl->integer('category_id')->unsigned();
        });

        Schema::table('binaries_categories', function (Blueprint $tbl) {
            $tbl->primary(['binary_id', 'category_id']);
            $tbl->foreign('binary_id')->references('id')->on('binaries');
            $tbl->foreign('category_id')->references('id')->on('binary_categories');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('binaries_categories');
    }
}
