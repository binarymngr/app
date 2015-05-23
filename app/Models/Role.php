<?php namespace App\Models;

final class Role extends RESTModel
{
    const ROLE_ID_ADMIN = 1;

    protected $fillable = ['name', 'description'];
    protected $visible  = ['id', 'name', 'description'];

    public static $relationsData = [
        'users' => [self::BELONGS_TO_MANY, 'App\Models\User', 'table' => 'users_roles']
    ];
    public static $rules = [
        'name' => 'required|between:1,50'  # TODO: unique
    ];


    public function delete()
    {
        $this->users()->detach();
        return parent::delete();
    }

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

    public function isDeletableByUser(User $user)
    {
        return $user->isAdmin();
    }

    public function isUpdatableByUser(User $user)
    {
        return $user->isAdmin();
    }

    public function isVisibleToUser(User $user)
    {
        return $user->isAdmin() || $this->users->contains($user->id);
    }
}
