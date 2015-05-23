<?php namespace App\Http\Helpers;

use Auth;
use Illuminate\Http\Request;

trait RestrictedDeletable
{
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
