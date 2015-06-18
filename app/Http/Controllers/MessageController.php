<?php

namespace App\Http\Controllers;

use App\Http\Helpers\RestrictedDeletable;
use App\Http\Helpers\UserDependentGetAll;

final class MessageController extends RESTController
{
    use RestrictedDeletable, UserDependentGetAll;


    /**
     * @{inherit}
     */
    protected static $model = 'App\Models\Message';


    /**
     * @{inherit}
     */
    public function __construct()
    {
        $this->middleware('forceVisibleToUser', ['only' => [
            'getById'
        ]]);
    }
}
