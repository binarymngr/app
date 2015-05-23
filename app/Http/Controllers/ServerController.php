<?php namespace App\Http\Controllers;

use App\Http\Helpers\RestrictedDeletable;
use App\Http\Helpers\RestrictedUpdatable;
use App\Http\Helpers\UserDependentGetAll;
use App\Models\Server;
use Auth;
use Illuminate\Http\Request;

final class ServerController extends RESTController
{
    use RestrictedDeletable, RestrictedUpdatable, UserDependentGetAll;


    protected static $model = 'App\Models\Server';


    public function __construct()
    {
        $this->middleware('forceVisibleToUser', ['only' => [
            'getById',
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
}
