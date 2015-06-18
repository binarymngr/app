<?php

namespace App\Http\Helpers;

use Auth;

trait UserDependentGetAll
{
    /**
     * Returns a collection of records visible to the request user.
     *
     * Attn: This trait is meant to be used in \App\Controllers\RESTController s.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAll()
    {
        $model = static::$model;
        return $model::getAllVisibleToUser(Auth::user());
    }
}
