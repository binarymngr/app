<?php namespace App\Http\Controllers;

use App\Http\Helpers\RestrictedUpdatable;
use App\Http\Helpers\UserDependentGetAll;
use App\Models\User;
use Auth;

final class UserController extends RESTController
{
    use RestrictedUpdatable, UserDependentGetAll;


    protected static $model = 'App\Models\User';


    public function __construct()
    {
        $this->middleware('forceAdminRole', ['only' => [
            'create',
            'deleteById'
        ]]);
        $this->middleware('forceVisibleToUser', ['only' => [
            'getById'
        ]]);
    }
}
