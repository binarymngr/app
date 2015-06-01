<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateServersBinaryVersionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('servers_binary_versions', function(Blueprint $tbl)
        {
            $tbl->integer('server_id')->unsigned();
            $tbl->integer('binary_version_id')->unsigned();
        });

        Schema::table('servers_binary_versions', function (Blueprint $tbl) {
            $tbl->primary(['server_id', 'binary_version_id']);
            $tbl->foreign('server_id')->references('id')->on('servers')->onDelete('cascade');
            $tbl->foreign('binary_version_id')->references('id')->on('binary_versions')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('servers_binary_versions');
    }
}
