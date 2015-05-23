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


    public function delete()
    {
        $this->roles()->detach();
        return parent::delete();
    }

    public static function getAllVisibleToUser(User $user)
    {
        $users = User::all();
        if (!$user->isAdmin()) {
            $users = Collection::make($user);
        }
        return $users;
    }

    public function hasBinaries()
    {
        return !$this->binaries->isEmpty();
    }

    public function hasServers()
    {
        return !$this->servers->isEmpty();
    }

    public function isAdmin()
    {
        return $this->roles->contains(Role::ROLE_ID_ADMIN);
    }

    public function isDeletableByUser(User $user)
    {
        return $user->isAdmin();
    }

    public function isUpdatableByUser(User $user)
    {
        return $this->isVisibleToUser($user);
    }

    public function isVisibleToUser(User $user)
    {
        return $user->isAdmin() || $user === $this;
    }
}
