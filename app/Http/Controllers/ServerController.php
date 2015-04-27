<?php namespace App\Http\Controllers;

use App\Http\Controllers\RESTController;

class ServerController extends RESTController
{
    /**
     * @{inherit}
     */
    protected static $model = 'App\Server';
}
