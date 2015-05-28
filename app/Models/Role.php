<?php namespace App\Models;

use App\Exceptions\DeletingProtectedRecordException;
use Illuminate\Database\Eloquent\Collection;

final class Role extends RESTModel
{
    /**
     * Numeric ID of the admin role as it's stored in the DB.
     *
     * @var int
     */
    const ROLE_ID_ADMIN = 1;

    protected $appends  = ['user_ids'];
    protected $fillable = ['name', 'description'];
    protected $visible  = ['id', 'name', 'description', 'user_ids'];

    public static $relationsData = [
        'users' => [self::BELONGS_TO_MANY, 'App\Models\User', 'table' => 'users_roles']
    ];
    public static $rules = [
        'name' => 'required|between:1,50'  # TODO: unique
    ];


    /**
     * @{inherit}
     */
    public static function boot()
    {
        parent::boot();
        Role::deleting(function(Role $role)
        {
            if (Role::getProtectedRoles()->contains($role->id)) {
                throw new DeletingProtectedRecordException($role, 'Cannot delete the protected role.');
            }
            $role->users()->detach();
        });
    }

    /**
     * @{inherit}
     */
    public static function getAllVisibleToUser(User $user)
    {
        $roles = Role::all();
        if (!$user->isAdmin()) {
            $roles = $roles->reject(function($role) use ($user) {
                return !$role->isVisibleToUser($user);
            })->flatten();
        }
        return $roles;
    }

    /**
     * Returns a collection containing all roles protected from deletion.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getProtectedRoles()
    {
        return Collection::make([
            Role::find(Role::ROLE_ID_ADMIN)
        ]);
    }

    /**
     * Accessor for the virtual 'user_ids' attribute.
     *
     * @link http://laravel.com/docs/5.0/eloquent#converting-to-arrays-or-json
     *
     * @return array an array containing the user IDs
     */
    public function getUserIdsAttribute()
    {
        $user_ids = [];
        foreach ($this->users as $user) {
            $user_ids[] = $user->id;
        }
        return $user_ids;
    }

    /**
     * Checks if this role has users belonging to it.
     *
     * @return bool true if at least one user has this role
     */
    public function hasUsers()
    {
        return !$this->users->isEmpty();
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
        return $user->isAdmin();
    }

    /**
     * @{inherit}
     */
    public function isVisibleToUser(User $user)
    {
        return $user->isAdmin() || $this->users->contains($user->id);
    }
}
