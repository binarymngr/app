<?php

namespace App\Http\Controllers;

use App\Models\Server;
use Auth;
use Illuminate\Http\Request;

final class ServerController extends RESTController
{
    /**
     * @{inherit}
     */
    protected static $model = 'App\Models\Server';


    /**
     * @{inherit}
     *
     * @Override to set owner_id to the request user
     */
    public function create(Request $rqst)
    {
        $rqst->merge([
            'owner_id' => Auth::user()->id
        ]);
        return parent::create($rqst);
    }

    /**
     * @{inherit}
     *
     * @Override to sync the binary version IDS
     */
    public function putById(Request $rqst, $id)
    {
        $response = null;
        $user = Auth::user();
        $record = Server::find($id);
        if ($record === null) {
            abort(404, 'Record not found.');
        } elseif ($record->isUpdatableByUser($user)) {
            if ($record->validate() && $record->update()) {
                $binary_version_ids = $rqst->input('binary_version_ids');
                $record->binary_versions()->sync(is_array($binary_version_ids) ? $binary_version_ids : []);
                $response = $record;
            } else {
                $response = ['errors' => $record->errors()->all()];
            }
        } else {
            abort(401);
        }
        return $response;
    }
}
