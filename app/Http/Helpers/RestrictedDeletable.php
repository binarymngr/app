<?php

namespace App\Http\Helpers;

use Auth;

trait RestrictedDeletable
{
    /**
     * Deletes the record with the given ID (if exists).
     *
     * Attn: This trait is meant to be used in \App\Controllers\RESTController s.
     *
     * @param int $id the ID of the record to delete
     *
     * @return \Illuminate\Http\Response
     */
    public function deleteById($id)
    {
        $response = null;
        $model = static::$model;
        $record = $model::find($id);
        if ($record === null) {
            abort(404);
        } elseif ($record->isDeletableByUser(Auth::user())) {
            $record->delete();
            $response = response('', 204);
        } else {
            abort(401);
        }
        return $response;
    }
}
