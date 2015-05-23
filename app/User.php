<?php namespace App;

use App\Role;
use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use LaravelBook\Ardent\Ardent;

final class User extends Ardent implements AuthenticatableContract, CanResetPasswordContract
{
    use Authenticatable, CanResetPassword;

    public $autoHashPasswordAttributes    = true;
    public $autoHydrateEntityFromInput    = true;
    public $forceEntityHydrationFromInput = true;

    protected $dates    = ['created_at', 'updated_at'];
    protected $fillable = ['email', 'password'];
    protected $visible  = ['id', 'email'];

    public static $passwordAttributes = ['password'];
    public static $relationsData      = [
        'binaries' => [self::HAS_MANY, 'App\Binary'],
        'roles'    => [self::BELONGS_TO_MANY, 'App\Role', 'table' => 'users_roles'],
        'servers'  => [self::HAS_MANY, 'App\Server'],
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
}
