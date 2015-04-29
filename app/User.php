<?php namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use LaravelBook\Ardent\Ardent;

class User extends Ardent implements AuthenticatableContract, CanResetPasswordContract
{
    use Authenticatable, CanResetPassword;

    public $autoHydrateEntityFromInput    = true;
    public $forceEntityHydrationFromInput = true;
    public $autoHashPasswordAttributes    = true;

    protected $fillable  = ['email', 'password'];
    protected $dates     = ['created_at', 'updated_at'];
    public static $passwordAttributes = ['password'];
    public static $rules = [
        'email'    => 'required|email',  # TODO: unique
        'password' => 'required'
    ];
    protected $visible   = ['id', 'email'];

    public static $relationsData = [
        'binaries' => [
            self::HAS_MANY, 'App\Binary'
        ],
        'roles' => [
            self::BELONGS_TO_MANY, 'App\Role',
            'table' => 'users_roles'
        ],
        'servers' => [
            self::HAS_MANY, 'App\Server'
        ],
    ];
}
