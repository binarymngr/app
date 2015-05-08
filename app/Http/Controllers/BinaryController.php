<?php namespace App\Http\Controllers;

use App\Binary;
use App\Http\Controllers\RESTController;
use Auth;
use Illuminate\Http\Request;

final class BinaryController extends RESTController
{
    protected static $model = 'App\Binary';


    public function __construct()
    {
        $this->middleware('forceVisibleToUser', ['only' => 'getById']);
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
        return Binary::getAllVisibleToUser(Auth::user());
    }

    /**
     * @Override (to check if the operation is permited)
     */
    public function putById(Request $rqst, $id)
    {
        $response = null;
        $user = Auth::user();
        $binary = Binary::find($id);
        if ($binary === null) {
            abort(404, 'Binary not found.');
        } elseif ($binary->isVisibleToUser($user)) {
            if ($binary->validate() && $binary->update()) {
                $response = $binary;
            } else {
                $response = [
                    'errors' => $binary->errors()->all()
                ];
            }
        } else {
            abort(401);
        }
        return $response;
    }
}
