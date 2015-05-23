<?php namespace App;

use LaravelBook\Ardent\Ardent;

final class Role extends Ardent
{
    const ROLE_ID_ADMIN = 1;

    public $autoHydrateEntityFromInput    = true;
    public $forceEntityHydrationFromInput = true;

    protected $dates    = ['created_at', 'updated_at'];
    protected $fillable = ['name', 'description'];
    protected $visible  = ['id', 'name', 'description'];

    public static $relationsData = [
        'users' => [self::BELONGS_TO_MANY, 'App\User', 'table' => 'users_roles']
    ];
    public static $rules = [
        'name' => 'required|between:1,50'  # TODO: unique
    ];


    public function delete()
    {
        $this->users()->detach();
        return parent::delete();
    }
}
