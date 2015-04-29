<?php namespace App\Http\Controllers;

use App\Http\Controllers\RESTController;

class BinaryVersionController extends RESTController
{
    /**
     * @{inherit}
     */
    protected static $model = 'App\BinaryVersion';


    /**
     *
     */
    public function getServersForId($id)
    {
        $model  = static::$model;
        $record = $model::findOrFail($id);
        return $record->servers;
    }
}
