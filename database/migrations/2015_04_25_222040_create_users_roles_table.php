<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users_roles', function(Blueprint $tbl)
        {
            $tbl->integer('user_id')->unsigned();
            $tbl->integer('role_id')->unsigned();
        });

        Schema::table('users_roles', function(Blueprint $tbl)
        {
            $tbl->primary(['user_id', 'role_id']);
            $tbl->foreign('user_id')->references('id')->on('users');
            $tbl->foreign('role_id')->references('id')->on('roles');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users_roles');
    }
}
