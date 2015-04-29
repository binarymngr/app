<?php namespace App\Http\Controllers;

use App\Http\Controllers\RESTController;

class UserController extends RESTController
{
    /**
     * @{inherit}
     */
    protected static $model = 'App\User';


    /**
     *
     */
    public function getBinariesForId($id)
    {
        $model  = static::$model;
        $record = $model::findOrFail($id);
        return $record->binaries;
    }

    /**
     *
     */
    public function getRolesForId($id)
    {
        $model  = static::$model;
        $record = $model::findOrFail($id);
        return $record->roles;
    }

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
