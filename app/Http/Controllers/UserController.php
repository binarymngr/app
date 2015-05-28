<?php namespace App\Http\Controllers;

use App\Exceptions\DeletingProtectedRecordException;
use App\Http\Helpers\RestrictedUpdatable;
use App\Http\Helpers\UserDependentGetAll;
use App\Models\Role;
use App\Models\User;
use Auth;
use Illuminate\Http\Request;

final class UserController extends RESTController
{
    use RestrictedUpdatable, UserDependentGetAll;


    protected static $model = 'App\Models\User';


    public function __construct()
    {
        $this->middleware('forceAdminRole', ['only' => [
            'addRole',
            'create',
            'deleteById',
            'removeRole'
        ]]);
        $this->middleware('forceVisibleToUser', ['only' => [
            'getById'
        ]]);
    }

    /**
     * Adds the role defined in 'role_id' to the user.
     *
     * @param \Illuminate\Http\Request $rqst the accepted request
     * @param int                      $id   the user's ID
     *
     * @return \Illuminate\Http\Response an empty response
     */
    public function addRole(Request $rqst, $id)
    {
        $user = User::find($id);
        if ($user === null) {
            abort(404, 'User not found.');
        }
        $role_id = $rqst->input('role_id');
        if ($role_id === null) {
            abort(400, 'Required field role_id missing.');
        }
        $role = Role::find($role_id);
        if ($role === null) {
            abort(404, 'Role not found.');
        }
        $user->addRole($role);
        return response('', 204);
    }

    /**
     * @{inherit}
     *
     * @Override to catch exceptions when the last admin user gets deleted
     */
    public function deleteById($id)
    {
        try {
            return parent::deleteById($id);
        } catch (DeletingProtectedRecordException $ex) {
            abort(403, 'Cannot delete the last admin user.');
        }
    }

    /**
     * Removes the role defined in 'role_id' from the user.
     *
     * @param \Illuminate\Http\Request $rqst the accepted request
     * @param int                      $id   the user's ID
     *
     * @return \Illuminate\Http\Response an empty response
     */
    public function removeRole(Request $rqst, $id)
    {
        $user = User::find($id);
        if ($user === null) {
            abort(404, 'User not found.');
        }
        $role_id = $rqst->input('role_id');
        if ($role_id === null) {
            abort(400, 'Required field role_id missing.');
        }
        $role = Role::find($role_id);
        if ($role === null) {
            abort(404, 'Role not found.');
        }
        $user->removeRole($role);
        return response('', 204);
    }
}
