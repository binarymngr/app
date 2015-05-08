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

    /**
     * @Override (to prevent removing the admin role)
     */
    public function deleteById($id)
    {
        if ($i === Role::ADMIN_ROLE_ID) {
            return abort(403, 'The admin role can not be deleted.');
        }
        return parent::deleteById($id);
    }
}
