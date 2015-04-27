<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * @link https://github.com/laravel/laravel/blob/ce48990bf2b08b04e4f4b490422090854055e4af/database/migrations/2014_10_12_100000_create_password_resets_table.php
 */
class CreatePasswordResetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('password_resets', function(Blueprint $tbl)
        {
            $tbl->string('email')->index();
            $tbl->string('token')->index();
            $tbl->timestamp('created_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('password_resets');
    }
}
