<?php namespace App\Http\Controllers;

use App\Http\Helpers\RestrictedDeletable;
use App\Http\Helpers\RestrictedUpdatable;
use App\Http\Helpers\UserDependentGetAll;
use App\Models\Binary;
use App\Models\BinaryVersion;
use Auth;
use Illuminate\Http\Request;

final class BinaryVersionController extends RESTController
{
    use RestrictedDeletable, RestrictedUpdatable, UserDependentGetAll;


    protected static $model = 'App\Models\BinaryVersion';


    public function __construct()
    {
        $this->middleware('forceVisibleToUser', ['only' => [
            'getById'
        ]]);
    }

    /**
     * @{inherit}
     *
     * @Override to get the binary from the binary_id
     */
    public function create(Request $rqst)
    {
        $binary = Binary::find($rqst->input('binary_id'));
        if ($binary === null) {
            abort(404, 'Binary not found.');
        }
        if (!$binary->isVisibleToUser(Auth::user())) {
            abort(401);
        }
        return parent::create($rqst);
    }
}
