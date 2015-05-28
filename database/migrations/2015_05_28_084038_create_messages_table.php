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
            $tbl->integer('user_id')->unsigned();
            $tbl->timestamps();
        });

        Schema::table('messages', function (Blueprint $tbl) {
            $tbl->foreign('user_id')->references('id')->on('users');
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
