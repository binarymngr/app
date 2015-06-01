<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateServersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('servers', function(Blueprint $tbl)
        {
            $tbl->increments('id');
            $tbl->string('name', 75)->unique();
            $tbl->char('ipv4', 15)->unique();
            $tbl->integer('owner_id')->unsigned();
            $tbl->timestamps();
        });

        Schema::table('servers', function (Blueprint $tbl) {
            $tbl->foreign('owner_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('servers');
    }
}
