<?php namespace App\Http\Controllers;

use App\Http\Controllers\RESTController;
use App\Server;
use Auth;
use Illuminate\Http\Request;

final class ServerController extends RESTController
{
    protected static $model = 'App\Server';


    public function __construct()
    {
        $this->middleware('forceVisibleToUser', ['only' => [
            'deleteById',
            // 'getAll',
            'getById',
            // 'putById'
        ]]);
    }

    /**
     * @Override (to set owner_id)
     */
    public function create(Request $rqst)
    {
        $rqst->merge([
            'owner_id' => Auth::user()->id
        ]);
        return parent::create($rqst);
    }

    /**
     * @Override (to only return the user's own servers)
     */
    public function getAll()
    {
        return Server::getAllVisibleToUser(Auth::user());
    }

    /**
     * @Override (to check if the operation is permited)
     */
    public function putById(Request $rqst, $id)
    {
        $response = null;
        $user = Auth::user();
        $server = Server::find($id);
        if ($server === null) {
            abort(404, 'Server not found.');
        } elseif ($server->isVisibleToUser($user)) {
            if ($server->validate() && $server->update()) {
                $response = $server;
            } else {
                $response = [
                    'errors' => $server->errors()->all()
                ];
            }
        } else {
            abort(401);
        }
        return $response;
    }
}
