<?php namespace App\Http\Controllers;

use App\Http\Controllers\RESTController;

class RoleController extends RESTController
{
    /**
     * @{inherit}
     */
    protected static $model = 'App\Role';


    /**
     *
     */
    public function getUsersForId($id)
    {
        $model  = static::$model;
        $record = $model::findOrFail($id);
        return $record->users;
    }
}
