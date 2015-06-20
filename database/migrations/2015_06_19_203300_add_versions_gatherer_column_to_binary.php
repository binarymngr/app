<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddVersionsGathererColumnToBinary extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('binaries', function (Blueprint $tbl)
        {
            $tbl->string('versions_gatherer', 100)->nullable();
            $tbl->text('versions_gatherer_meta')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('binaries', function (Blueprint $tbl)
        {
            $tbl->dropColumn('versions_gatherer');
            $tbl->dropColumn('versions_gatherer_meta');
        });
    }
}
