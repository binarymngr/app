<?php namespace App\Http\Controllers;

use App\Http\Controllers\RESTController;

class BinaryCategoryController extends RESTController
{
    /**
     * @{inherit}
     */
    protected static $model = 'App\BinaryCategory';


    /**
     *
     */
    public function getBinariesForId($id)
    {
        $model  = static::$model;
        $record = $model::findOrFail($id);
        return $record->binaries;
    }
}
