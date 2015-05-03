<?php namespace App\Http\Controllers;

use App\Http\Controllers\RESTController;
use App\Role;

final class RoleController extends RESTController
{
    protected static $model = 'App\Role';


    public function __construct()
    {
        $this->middleware('forceAdminRole');
    }
}
