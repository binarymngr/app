<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJobsTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('jobs', function(Blueprint $tbl)
        {
            $tbl->bigIncrements('id');
            $tbl->string('queue');
            $tbl->text('payload');
            $tbl->tinyInteger('attempts')->unsigned();
            $tbl->tinyInteger('reserved')->unsigned();
            $tbl->unsignedInteger('reserved_at')->nullable();
            $tbl->unsignedInteger('available_at');
            $tbl->unsignedInteger('created_at');
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('jobs');
	}
}
