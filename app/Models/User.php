<?php namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Database\Eloquent\Collection;
use LaravelBook\Ardent\Ardent;

final class User extends RESTModel implements AuthenticatableContract, CanResetPasswordContract
{
    use Authenticatable, CanResetPassword;


    public $autoHashPasswordAttributes    = true;

    protected $fillable = ['email', 'password'];
    protected $visible  = ['id', 'email'];

    public static $passwordAttributes = ['password'];
    public static $relationsData      = [
        'binaries' => [self::HAS_MANY, 'App\Models\Binary'],
        'roles'    => [self::BELONGS_TO_MANY, 'App\Models\Role', 'table' => 'users_roles'],
        'servers'  => [self::HAS_MANY, 'App\Models\Server'],
    ];
    public static $rules = [
        'email'    => 'required|email',  # TODO: unique:users,email
        'password' => 'required'
    ];


    /**
     * @{inherit}
     *
     * @Override to detach the groups before deletion
     */
    public function delete()
    {
        $this->roles()->detach();
        return parent::delete();
    }

    /**
     * @{inherit}
     */
    public static function getAllVisibleToUser(User $user)
    {
        $users = User::all();
        if (!$user->isAdmin()) {
            $users = Collection::make($user);
        }
        return $users;
    }

    /**
     * Checks if the user owns binaries.
     *
     * @return Bool true if the user owns at least one binary
     */
    public function hasBinaries()
    {
        return !$this->binaries->isEmpty();
    }

    /**
     * Checks if the user owns servers.
     *
     * @return Bool true if the user owns at least one server
     */
    public function hasServers()
    {
        return !$this->servers->isEmpty();
    }

    /**
     * Checks if the user is admin (has the admin role).
     *
     * @return Bool true if the user has the admin role
     */
    public function isAdmin()
    {
        return $this->roles->contains(Role::ROLE_ID_ADMIN);
    }

    /**
     * @{inherit}
     */
    public function isDeletableByUser(User $user)
    {
        return $user->isAdmin();
    }

    /**
     * @{inherit}
     */
    public function isUpdatableByUser(User $user)
    {
        return $this->isVisibleToUser($user);
    }

    /**
     * @{inherit}
     */
    public function isVisibleToUser(User $user)
    {
        return $user->isAdmin() || $user === $this;
    }
}
