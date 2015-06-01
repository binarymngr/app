<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        global $app;

        Schema::create('roles', function(Blueprint $tbl)
        {
            $tbl->increments('id');
            $tbl->string('name', 50)->unique();
            $tbl->text('description')->nullable();
            $tbl->timestamps();
        });

        if ($app['config']->get('database.default') == 'mysql') {
            DB::unprepared("CREATE TRIGGER prevent_protected_roles_deletion
            BEFORE DELETE ON roles
            FOR EACH ROW
            BEGIN
                IF OLD.id IN (1,2) THEN
                    SIGNAL SQLSTATE '45000'
                    SET MESSAGE_TEXT = 'This record is protected and cannot be removed.';
                END IF;
            END");
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('roles');
    }
}
