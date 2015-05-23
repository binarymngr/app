<?php namespace App\Http\Controllers;

use App\Http\Controllers\RESTController;
use App\Role;

final class RoleController extends RESTController
{
    protected static $model = 'App\Role';


    public function __construct()
    {
        $this->middleware('forceAdminRole', ['only' => [
            'create',
            'deleteById',
            'putById'
        ]]);
        $this->middleware('forceVisibleToUser', ['only' => [
            // 'getAll',
            'getById'
        ]]);
    }

    /**
     * @Override (to prevent removing the admin role)
     */
    public function deleteById($id)
    {
        if ($i === Role::ROLE_ID_ADMIN) {
            abort(403, 'The admin role can not be deleted.');
        }
        return parent::deleteById($id);
    }

    /**
     * @Override (to only return the user's own roles)
     */
    public function getAll()
    {
        return Role::getAllVisibleToUser(Auth::user());
    }
}
