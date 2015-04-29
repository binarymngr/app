<?php namespace App;

use LaravelBook\Ardent\Ardent;

class BinaryVersion extends Ardent
{
    public $autoHydrateEntityFromInput    = true;
    public $forceEntityHydrationFromInput = true;

    protected $fillable  = ['identifier', 'note', 'eol', 'binary_id'];
    protected $dates     = ['eol', 'created_at', 'updated_at'];
    public static $rules = [
        'identifier' => 'required|between:1,64',
        'eol'        => 'date',
        'binary_id'  => 'required|exists:binaries,id|integer'
    ];
    protected $visible   = ['id', 'identifier', 'note', 'eol', 'binary_id'];

    public static $relationsData = [
        'binary' => [
            self::BELONGS_TO, 'App\Binary',
            'table' => 'binaries'
        ],
        'servers' => [
            self::BELONGS_TO_MANY, 'App\Server',
            'table' => 'servers_binary_versions'
        ]
    ];
}
