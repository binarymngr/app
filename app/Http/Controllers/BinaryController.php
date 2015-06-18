<?php

namespace App\Http\Controllers;

use App\Http\Helpers\RestrictedDeletable;
use App\Http\Helpers\UserDependentGetAll;
use App\Models\Binary;
use Auth;
use Illuminate\Http\Request;

final class BinaryController extends RESTController
{
    use RestrictedDeletable, UserDependentGetAll;


    /**
     * @{inherit}
     */
    protected static $model = 'App\Models\Binary';


    /**
     * @{inherit}
     */
    public function __construct()
    {
        $this->middleware('forceVisibleToUser', ['only' => [
            'getById'
        ]]);
    }

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

    /**
     * @{inherit}
     *
     * @Override to sync the user's roles
     */
     public function putById(Request $rqst, $id)
     {
         $response = null;
         $user = Auth::user();
         $record = Binary::find($id);
         if ($record === null) {
             abort(404, 'Record not found.');
         } elseif ($record->isUpdatableByUser($user)) {
             if ($record->validate() && $record->update()) {
                 $category_ids = $rqst->input('binary_category_ids');
                 $record->categories()->sync(is_array($category_ids) ? $category_ids : []);
                 $response = $record;
             } else {
                 $response = [
                     'errors' => $record->errors()->all()
                 ];
             }
         } else {
             abort(401);
         }
         return $response;
     }
}
