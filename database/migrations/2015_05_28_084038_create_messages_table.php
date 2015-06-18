<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('messages', function(Blueprint $tbl)
        {
            $tbl->increments('id');
            $tbl->string('title', 100);
            $tbl->text('body');
            $tbl->integer('binary_id')->unsigned()->nullable();
            $tbl->integer('binary_version_id')->unsigned()->nullable();
            $tbl->integer('server_id')->unsigned()->nullable();
            $tbl->integer('user_id')->unsigned();
            $tbl->timestamps();
        });

        Schema::table('messages', function (Blueprint $tbl) {
            $tbl->foreign('binary_id')->references('id')->on('binaries')->onDelete('cascade');
            $tbl->foreign('binary_version_id')->references('id')->on('binary_versions')->onDelete('cascade');
            $tbl->foreign('server_id')->references('id')->on('servers')->onDelete('cascade');
            $tbl->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('messages');
    }
}
