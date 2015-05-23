<?php namespace App\Http\Helpers;

use Auth;
use Illuminate\Http\Request;

trait RestrictedUpdatable
{
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
