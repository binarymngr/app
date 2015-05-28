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

    protected $appends  = ['binary_ids', 'role_ids', 'server_ids'];
    protected $fillable = ['email', 'password'];
    protected $visible  = ['id', 'email', 'binary_ids', 'role_ids', 'server_ids'];

    public static $passwordAttributes = ['password'];
    public static $relationsData      = [
        'binaries' => [self::HAS_MANY, 'App\Models\Binary', 'foreignKey' => 'owner_id'],
        'messages' => [self::HAS_MANY, 'App\Models\Messages'],
        'roles'    => [self::BELONGS_TO_MANY, 'App\Models\Role', 'table' => 'users_roles'],
        'servers'  => [self::HAS_MANY, 'App\Models\Server', 'foreignKey' => 'owner_id'],
    ];
    public static $rules = [
        'email'    => 'required|email',  # TODO: unique:users,email
        'password' => 'required'
    ];


    /**
     * Adds the user to the role / the role to this user.
     *
     * @param \App\Models\Role $role the role to add
     *
     * @return Void
     */
    public function addRole(Role $role)
    {
        $this->roles()->attach($role);
    }

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
     * Accessor for the virtual 'binary_ids' attribute.
     *
     * @link http://laravel.com/docs/5.0/eloquent#converting-to-arrays-or-json
     *
     * @return array an array containing the binary IDs
     */
    public function getBinaryIdsAttribute()
    {
        $binary_ids = [];
        foreach ($this->binaries as $binary) {
            $binary_ids[] = $binary->id;
        }
        return $binary_ids;
    }

    /**
     * Accessor for the virtual 'role_ids' attribute.
     *
     * @link http://laravel.com/docs/5.0/eloquent#converting-to-arrays-or-json
     *
     * @return array an array containing the role IDs
     */
    public function getRoleIdsAttribute()
    {
        $role_ids = [];
        foreach ($this->roles as $role) {
            $role_ids[] = $role->id;
        }
        return $role_ids;
    }

    /**
     * Accessor for the virtual 'server_ids' attribute.
     *
     * @link http://laravel.com/docs/5.0/eloquent#converting-to-arrays-or-json
     *
     * @return array an array containing the server IDs
     */
    public function getServerIdsAttribute()
    {
        $server_ids = [];
        foreach ($this->servers as $server) {
            $server_ids[] = $server->id;
        }
        return $server_ids;
    }

    /**
     * Checks if messages exist for this user.
     *
     * @return Bool true if a message for this user exists
     */
    public function hasMessages()
    {
        $this->messages->isEmpty();
    }

    /**
     * Checks if the belongs to at least one role.
     *
     * @return Bool true if the user belongs to at least one role
     */
    public function hasRoles()
    {
        return !$this->roles->isEmpty();
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

    /**
     * Checks if the user owns binaries.
     *
     * @return Bool true if the user owns at least one binary
     */
    public function ownsBinaries()
    {
        return !$this->binaries->isEmpty();
    }

    /**
     * Checks if the user owns servers.
     *
     * @return Bool true if the user owns at least one server
     */
    public function ownsServers()
    {
        return !$this->servers->isEmpty();
    }

    /**
     * Removes the user from the role / the role from this user.
     *
     * @param \App\Models\Role $role the role to remove
     *
     * @return Void
     */
    public function removeRole(Role $role)
    {
        $this->roles()->detach($role);
    }
}
