<?php namespace App\Http\Controllers;

use App\Http\Controllers\RESTController;

class BinaryController extends RESTController
{
    /**
     * @{inherit}
     */
    protected static $model = 'App\Binary';


    /**
     *
     */
    public function getCategoriesForId($id)
    {
        $model  = static::$model;
        $record = $model::findOrFail($id);
        return $record->categories;
    }

    /**
     *
     */
    public function getVersionsForId($id)
    {
        $model  = static::$model;
        $record = $model::findOrFail($id);
        return $record->versions;
    }
}
