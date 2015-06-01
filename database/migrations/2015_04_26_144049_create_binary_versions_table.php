<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBinaryVersionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('binary_versions', function(Blueprint $tbl)
        {
            $tbl->increments('id');
            $tbl->string('identifier', 64);
            $tbl->text('note')->nullable();
            $tbl->date('eol')->nullable();
            $tbl->integer('binary_id')->unsigned();
            $tbl->timestamps();
        });

        Schema::table('binary_versions', function (Blueprint $tbl) {
            $tbl->foreign('binary_id')->references('id')->on('binaries')->onDelete('cascade');
            // TODO: unique together: identfier, binary_id
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('binary_versions');
    }
}
