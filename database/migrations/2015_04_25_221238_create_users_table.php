<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/*
 * @link https://raw.githubusercontent.com/laravel/laravel/ce48990bf2b08b04e4f4b490422090854055e4af/database/migrations/2014_10_12_000000_create_users_table.php
 */
class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function(Blueprint $tbl)
        {
            $tbl->increments('id');
            $tbl->string('email')->unique();
            $tbl->string('password', 60);
            $tbl->rememberToken();
            $tbl->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
