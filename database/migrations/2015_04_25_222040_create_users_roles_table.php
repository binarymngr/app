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
        global $app;

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

        if ($app['config']->get('database.default') == 'mysql') {
            DB::unprepared("CREATE TRIGGER prevent_last_admin_deletion
            BEFORE DELETE ON users_roles
            FOR EACH ROW
            BEGIN
                IF OLD.role_id = 1
                AND (SELECT COUNT(*) FROM users_roles WHERE role_id = 1 GROUP BY role_id) = 1
                THEN
                    SIGNAL SQLSTATE '45000'
                    SET MESSAGE_TEXT = 'Cannot remove the last admin role membership.';
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
        Schema::dropIfExists('users_roles');
    }
}
