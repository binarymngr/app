<?php namespace App;

use LaravelBook\Ardent\Ardent;

class Binary extends Ardent
{
    public $autoHydrateEntityFromInput    = true;
    public $forceEntityHydrationFromInput = true;

    protected $fillable  = ['name', 'description', 'homepage', 'owner_id'];
    protected $dates     = ['created_at', 'updated_at'];
    public static $rules = [
        'name'     => 'required|between:1,100', // TODO: unique
        'homepage' => 'url|between:1,100',
        'owner_id' => 'required|exists:users,id|integer'
    ];
    protected $visible   = ['id', 'name', 'description', 'homepage', 'owner', 'categories'];
    protected $with      = ['owner', 'categories'];

    public static $relationsData = [
        'categories' => [
            self::BELONGS_TO_MANY, 'App\BinaryCategory',
            'table' => 'binaries_categories', 'otherKey' => 'category_id'
        ],
        'owner' => [
            self::BELONGS_TO, 'App\User',
            'table' => 'users', 'foreignKey' => 'owner_id', 'otherKey' => 'id'
        ],
        'versions' => [
            self::HAS_MANY, 'App\BinaryVersion'
        ],
    ];
}
