<?php namespace App\Http\Controllers;

use App\Http\Controllers\RESTController;
use App\User;

final class UserController extends RESTController
{
    protected static $model = 'App\User';


    public function __construct()
    {
        $this->middleware('forceAdminRole', ['only' => [
            'create',
            'deleteById'
        ]]);
        $this->middleware('forceVisibleToUser', ['only' => [
            // 'getAll',
            'getById',
            'putById'
        ]]);
    }

    /**
     * @Override (to only return the user's own record)
     */
    public function getAll()
    {
        return User::getAllVisibleToUser(Auth::user());
    }
}
