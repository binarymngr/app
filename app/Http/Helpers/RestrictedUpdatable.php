<?php

namespace App\Http\Helpers;

use Auth;
use Illuminate\Http\Request;

trait RestrictedUpdatable
{
    /**
     * Updates the record with the given ID with the values from the request.
     *
     * Attn: This trait is meant to be used in \App\Controllers\RESTController s.
     *
     * @param \Illuminate\Http\Request $rqst the incoming request
     * @param int                      $id   the ID of the record to update
     *
     * @return \Illuminate\Http\Response
     */
    public function putById(Request $rqst, $id)
    {
        $response = null;
        $user = Auth::user();
        $model = static::$model;
        $record = $model::find($id);
        if ($record === null) {
            abort(404, 'Record not found.');
        } elseif ($record->isUpdatableByUser($user)) {
            if ($record->validate() && $record->update()) {
                $response = $record;
            } else {
                $response = [
                    'errors' => $record->errors()->all()
                ];
            }
        } else {
            abort(401);
        }
        return $response;
    }
}
