<?php

use App\Models\Role;
use Illuminate\Database\Seeder;

final class RoleTableSeeder extends Seeder
{
    /**
     * @{inherit}
     */
    public function run()
    {
        DB::table('roles')->delete();

        Role::create([
            'name' => 'admins',
            'description' => 'Full-privileged admin accounts.'
        ]);
        Role::create([
            'name' => 'users',
            'description' => 'Regular user accounts.'
        ]);
    }
}
