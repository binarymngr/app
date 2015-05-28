<?php namespace App\Http\Controllers;

use App\Http\Helpers\RestrictedDeletable;
use App\Http\Helpers\RestrictedUpdatable;
use App\Http\Helpers\UserDependentGetAll;
use App\Models\Binary;
use Auth;
use Illuminate\Http\Request;

final class BinaryController extends RESTController
{
    use RestrictedDeletable, RestrictedUpdatable, UserDependentGetAll;


    protected static $model = 'App\Models\Binary';


    public function __construct()
    {
        $this->middleware('forceVisibleToUser', ['only' => [
            'getById'
        ]]);
    }

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
}
