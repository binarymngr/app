<?php namespace App\Http\Helpers;

use Auth;

trait UserDependentGetAll
{
    public function getAll()
    {
        $model = static::$model;
        return $model::getAllVisibleToUser(Auth::user());
    }
}
