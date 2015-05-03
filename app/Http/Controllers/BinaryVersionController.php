<?php namespace App\Http\Controllers;

use App\Binary;
use App\BinaryVersion;
use App\Http\Controllers\RESTController;
use Auth;
use Illuminate\Http\Request;

final class BinaryVersionController extends RESTController
{
    protected static $model = 'App\BinaryVersion';


    /**
     * @Override (to set check owner_id on binary)
     */
    public function create(Request $rqst)
    {
        $binary = Binary::find($rqst->input('binary_id'));
        if ($binary !== null && $binary->isVisibleToUser(Auth::user())) {
            return parent::create($rqst);
        }
        return [
            'errors' => 'Unauthorized',
            'status' => 401
        ];
    }

    /**
     * @Override (to only return the versions of binaries the user owns)
     */
    public function getAll()
    {
        return BinaryVersion::getAllVisibleToUser(Auth::user());
    }

    /**
     * @Override (to check if the operation is permited)
     */
    public function putById(Request $rqst, $id)
    {
        $response = null;
        $user = Auth::user();
        $version = BinaryVersion::find($id);
        if ($version === null) {
            $response = [
                'errors' => 'Not found',
                'status' => 404
            ];
        } elseif ($version->isVisibleToUser($user)) {
            if ($version->validate() && $version->update()) {
                $response = $version;
            } else {
                $response = [
                    'errors' => $version->errors()->all()
                ];
            }
        } else {
            $response = [
                'errors' => 'Unauthorized',
                'status' => 401
            ];
        }
        return $response;
    }
}
