<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;

final class ServerController extends RESTController
{
    /**
     * @{inherit}
     */
    protected static $model = 'App\Models\Server';


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
