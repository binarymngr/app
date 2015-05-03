<?php namespace App\Http\Controllers;

use App\Http\Controllers\RESTController;
use App\User;

final class UserController extends RESTController
{
    protected static $model = 'App\User';


    public function __construct()
    {
        $this->middleware('forceAdminRole');
    }
}
