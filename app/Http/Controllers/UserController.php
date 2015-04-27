<?php namespace App\Http\Controllers;

use App\Http\Controllers\RESTController;

class UserController extends RESTController
{
    /**
     * @{inherit}
     */
    protected static $model = 'App\User';
}
