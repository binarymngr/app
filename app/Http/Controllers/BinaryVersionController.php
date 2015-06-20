<?php

namespace App\Http\Controllers;

use App\Models\Binary;
use Auth;
use Illuminate\Http\Request;

final class BinaryVersionController extends RESTController
{
    /**
     * @{inherit}
     */
    protected static $model = 'App\Models\BinaryVersion';


    /**
     * @{inherit}
     *
     * @Override to get the binary from the binary_id
     */
    public function create(Request $rqst)
    {
        $binary = Binary::find($rqst->input('binary_id'));
        if ($binary === null) {
            abort(404, 'Binary not found.');
        }
        if (!$binary->isVisibleToUser(Auth::user())) {
            abort(401);
        }
        return parent::create($rqst);
    }
}
