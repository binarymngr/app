<?php namespace App;

use LaravelBook\Ardent\Ardent;

class Server extends Ardent
{
    public $autoHydrateEntityFromInput    = true;
    public $forceEntityHydrationFromInput = true;

    protected $fillable  = ['name', 'ipv4', 'owner_id'];
    protected $dates     = ['created_at', 'updated_at'];
    public static $rules = [
        'name'     => 'required|between:1,75',  # TODO: unique
        'ipv4'     => 'required|ip|between:7,15',  # TODO: unique
        'owner_id' => 'required|exists:users,id|integer'
    ];
    protected $visible   = ['id', 'name', 'ipv4', 'owner_id'];

    public static $relationsData = [
        'owner' => [
            self::BELONGS_TO, 'App\User',
            'foreignKey' => 'owner_id'
        ],
        'binaries' => [
            self::BELONGS_TO_MANY, 'App\BinaryVersion',
            'table' => 'servers_binary_versions'
        ]
    ];
}
