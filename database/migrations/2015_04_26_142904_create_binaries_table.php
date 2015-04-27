<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBinariesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('binaries', function(Blueprint $tbl)
        {
            $tbl->increments('id');
            $tbl->string('name', 100)->unique();
            $tbl->text('description')->nullable();
            $tbl->string('homepage', 100)->nullable();
            $tbl->integer('owner_id')->unsigned();
            $tbl->timestamps();
        });

        Schema::table('binaries', function (Blueprint $tbl) {
            $tbl->foreign('owner_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('binaries');
    }
}
