<?php namespace App\Http\Controllers;

use App\Http\Helpers\RestrictedUpdatable;
use App\Http\Helpers\UserDependentGetAll;
use App\Models\Role;
use App\Models\User;
use Auth;

final class UserController extends RESTController
{
    use RestrictedUpdatable, UserDependentGetAll;


    protected static $model = 'App\Models\User';


    public function __construct()
    {
        $this->middleware('forceAdminRole', ['only' => [
            'create',
            'deleteById'
        ]]);
        $this->middleware('forceVisibleToUser', ['only' => [
            'getById'
        ]]);
    }

    /**
     * @{inherit}
     *
     * @Override to prevent deleting the last admin
     */
    public function deleteById($id)
    {
        $user = User::find($id);
        if ($user === null) {
            abort(404, 'User not found.');
        }
        if ($user->isAdmin() && Role::find(Role::ROLE_ID_ADMIN)->users->count() === 1) {
            abort(403, 'Cannot delete the last admin user.');
        }
        return parent::deleteById($id);
    }
}
