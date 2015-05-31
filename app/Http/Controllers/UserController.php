<?php namespace App\Http\Controllers;

use App\Exceptions\DeletingProtectedRecordException;
use App\Http\Helpers\RestrictedUpdatable;
use App\Http\Helpers\UserDependentGetAll;
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
}
