<?php namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

abstract class RESTController extends Controller
{
    /**
     * Stores the full name of the model definition this controller acts on.
     *
     * @var String
     */
    protected static $model;


    /**
     * Creates a new record with the data provided by the request body.
     *
     * @method POST
     *
     * @return Response
     */
    public function create()
    {
        $response = null;
        $record   = new static::$model;
        if ($record->validate() && $record->save()) {
            $response = $record;
        } else {
            $response = [
                'errors' => $record->errors()->all()
            ];
        }

        return $response;
    }

    /**
     * Deletes the record with the given ID (if exists).
     *
     * TODO: detach() relations
     *
     * @method DELETE
     *
     * @param int $id the record's ID
     *
     * @return Response
     */
    public function deleteById($id)
    {
        $model  = static::$model;
        $record = $model::findOrFail($id);
        $record->delete();

        return $record;
    }

    /**
     * Returns an array of all records.
     *
     * @method GET
     *
     * @return Response
     */
    public function getAll()
    {
        $model = static::$model;
        return $model::all();
    }

    /**
     * Returns the record with the given primary key ID.
     *
     * @method GET
     *
     * @param int $id the record to get
     *
     * @return Response
     */
    public function getById($id)
    {
        $model = static::$model;
        return $model::findOrFail($id);
    }

    /**
     * Returns a list of allowed HTTP methods on the requested resource.
     *
     * @method OPTIONS
     *
     * @return Response
     */
    public function optionsForAll()
    {
        return response('', 200)->header('Allow', 'GET, HEAD, OPTIONS, POST');
    }

    /**
     * Returns a list of allowed HTTP methods on the requested resource.
     *
     * @method OPTIONS
     *
     * @param int $id the ID of the record to get the options for
     *
     * @return Response
     */
    public function optionsForId($id)
    {
        return response('', 200)->header('Allow', 'DELETE, GET, HEAD, OPTIONS, PUT');
    }

    /**
     * Updates the record with the given ID with the values provided by the PUT request.
     *
     * TODO: doesn't work
     *
     * @method PUT
     *
     * @param Request $rqst the PUT request
     * @param int     $id   the ID of the record to update
     *
     * @return Response
     */
    public function putById($id)
    {
        $response = null;
        $model    = static::$model;
        $record   = $model::findOrFail($id);
        if ($record->updateUniques() && $record->validateUniques() && $record->validate() && $record->save()) {
            $response = $record;
        } else {
            $response = [
                'errors' => $record->errors()->all()
            ];
        }

        return $response;
    }
}
