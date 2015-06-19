<?php

namespace App\Console\Commands;

use App\Models\Role;
use App\Models\User;
use Exception;
use Illuminate\Console\Command;

final class CreateAdminUser extends Command
{
    protected $name = 'binarymngr:create-admin-user';
    protected $description = 'Creates a new admin user.';


    /**
     * @{inherit}
     */
    public function fire()
    {
        $email = $this->ask('Email address');
        $password = null; $password_confirm = null;
        while ($password === null || $password != $password_confirm) {
            $password = $this->secret('Password');
            $password_confirm = $this->secret('Confirm password');
            if ($password != $password_confirm) {
                $this->comment('Passwords did not match. Please try again.');
            }
        }

        $admin = new User;
        $admin->email = $email;
        $admin->password = $password;
        try {
            if ($admin->validate() && $admin->save()) {
                $admin->roles()->attach(Role::find(Role::ROLE_ID_ADMIN));
                return $this->info('Sucessfully created the new admin user.');
            } else {
                return $this->error($admin->errors()->all());
            }
        } catch (Exception $ex) {
            return $this->error('An error encountered.');
        }
    }
}
