<?php namespace App\Http\Controllers;

use App\Http\Controllers\RESTController;
use App\Http\Helpers\UserDependentGetAll;
use App\Models\Role;
use Auth;

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
     * @Override to prevent removing the admin role
     */
    public function deleteById($id)
    {
        if ($id === Role::ROLE_ID_ADMIN) {
            abort(403, 'The admin role can not be deleted.');
        }
        return parent::deleteById($id);
    }
}
