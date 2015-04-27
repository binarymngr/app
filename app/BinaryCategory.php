<?php namespace App;

use LaravelBook\Ardent\Ardent;

class BinaryCategory extends Ardent
{
    public $autoHydrateEntityFromInput    = true;
    public $forceEntityHydrationFromInput = true;

    protected $fillable  = ['name', 'description'];
    protected $dates     = ['created_at', 'updated_at'];
    public static $rules = [
        'name'        => 'required|between:1,75' // TODO: unique
    ];
    protected $visible   = ['id', 'name', 'description'];

    public static $relationsData = [
        'binaries' => [
            self::HAS_MANY, 'App\Binary'
        ]
    ];
}
