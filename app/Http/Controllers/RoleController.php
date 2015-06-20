<?php

namespace App\Http\Controllers;

use App\Exceptions\DeletingProtectedRecordException;
use Illuminate\Database\QueryException;

final class RoleController extends RESTController
{
    /**
     * @{inherit}
     */
    protected static $model = 'App\Models\Role';


    /**
     * @{inherit}
     */
    public function __construct()
    {
        $this->middleware('forceAdminRole', ['only' => [
            'create',
            'deleteById',
            'putById'
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
        } catch (QueryException $ex) {
            if ((int)$ex->getCode() === 45000) {
                abort(403, 'The given role is protected and can not be deleted.');  # TODO: non-static error message
            }
            throw $ex;
        }
    }
}
