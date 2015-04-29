<?php namespace App;

use LaravelBook\Ardent\Ardent;

class Role extends Ardent
{
    public $autoHydrateEntityFromInput    = true;
    public $forceEntityHydrationFromInput = true;

    protected $fillable  = ['name', 'description'];
    protected $dates     = ['created_at', 'updated_at'];
    public static $rules = [
        'name' => 'required|between:1,50'  # TODO: unique
    ];
    protected $visible   = ['id', 'name', 'description'];

    public static $relationsData = [
        'users' => [
            self::BELONGS_TO_MANY, 'App\User',
            'table' => 'users_roles'
        ]
    ];
}
