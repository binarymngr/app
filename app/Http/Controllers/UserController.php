<?php namespace App\Http\Controllers;

use App\Exceptions\DeletingProtectedRecordException;
use App\Http\Helpers\UserDependentGetAll;
use App\Models\User;
use Auth;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

final class UserController extends RESTController
{
    use UserDependentGetAll;


    /**
     * @{inherit}
     */
    protected static $model = 'App\Models\User';


    /**
     * @{inherit}
     */
    public function __construct()
    {
        $this->middleware('forceAdminRole', ['only' => [
            'create',
            'deleteById',
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
        } catch (QueryException $ex) {
            if ((int)$ex->getCode() === 45000) {
                abort(403, 'Cannot delete the last admin user.');  # TODO: non-static error message
            }
            throw $ex;
        }
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
         $record = User::find($id);
         if ($record === null) {
             abort(404, 'Record not found.');
         } elseif ($record->isUpdatableByUser($user)) {
             if ($record->validate() && $record->update()) {
                 $role_ids = $rqst->input('role_ids');
                 if (is_array($role_ids)) {  # TODO: doesn't allow no role
                    try {
                        $record->roles()->sync($role_ids);
                    } catch (QueryException $ex) {
                        if ((int)$ex->getCode() === 45000) {
                            abort(403, 'Cannot remove the admin role membership.');  # TODO: non-static error message
                        }
                        throw $ex;
                    }
                 }
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
