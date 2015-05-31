<?php namespace App\Http\Controllers;

use App\Exceptions\DeletingProtectedRecordException;
use App\Http\Helpers\UserDependentGetAll;

final class RoleController extends RESTController
{
    use UserDependentGetAll;


    protected static $model = 'App\Models\Role';


    public function __construct()
    {
        $this->middleware('forceAdminRole', ['only' => [
            'create',
            'deleteById',
            'putById'
        ]]);
        $this->middleware('forceVisibleToUser', ['only' => [
            'getById'
        ]]);
    }

    /**
     * @{inherit}
     *
     * @Override to catch exceptions for protected roles
     */
    public function deleteById($id)
    {
        try {
            return parent::deleteById($id);
        } catch (DeletingProtectedRecordException $ex) {
            abort(403, 'The given role is protected and can not be deleted.');
        }
    }
}
