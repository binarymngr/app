<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;

/**
 * Base class for all controllers offering a RESTful API.
 *
 * @abstract
 */
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
     * @param \Illuminate\Http\Request $rqst the incoming request
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $rqst)
    {
        $response = null;
        $record = new static::$model;
        # TODO: unique checks
        if ($record->validate() && $record->save()) {
            $response = response($record, 201);
            $response->header('Location', url($rqst->url().'/:id', [
                'id' => $record->id
            ]));
        } else {
            $response = ['errors' => $record->errors()->all()];
        }
        return $response;
    }

    /**
     * Deletes the record with the given ID (if exists).
     *
     * @param int $id the ID of the record to delete
     *
     * @return \Illuminate\Http\Response
     */
    public function deleteById($id)
    {
        $response = null;
        $model = static::$model;
        $record = $model::find($id);
        if ($record === null) {
            abort(404);
        } elseif ($record->isDeletableByUser(Auth::user())) {
            $record->delete();
            $response = response('', 204);
        } else {
            abort(401);
        }
        return $response;
    }

    /**
     * Returns a collection of all records.
     *
     * @return \Illuminate\Http\Response
     */
    public function getAll()
    {
        $model = static::$model;
        return $model::getAllVisibleToUser(Auth::user());
    }

    /**
     * Returns the record with the given primary key ID.
     *
     * @param int $id the primary key of the record to return
     *
     * @return \Illuminate\Http\Response
     */
    public function getById($id)
    {
        $response = null;
        $model = static::$model;
        $record = $model::find($id);
        if ($record === null) {
            abort(404);
        } elseif ($record->isVisibleToUser(Auth::user())) {
            $response = $record;
        } else {
            abort(401);
        }
        return $response;
    }

    /**
     * Returns a list of allowed HTTP methods on the requested resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function optionsForAll()
    {
        return response('', 200)->header('Allow', 'GET, HEAD, OPTIONS, POST');
    }

    /**
     * Returns a list of allowed HTTP methods on the requested resource.
     *
     * @param int $id the ID of the record to get the options for
     *
     * @return \Illuminate\Http\Response
     */
    public function optionsForId($id)
    {
        return response('', 200)->header('Allow', 'DELETE, GET, HEAD, OPTIONS, PUT');
    }

    /**
     * Updates the record with the given ID with the values provided by the PUT request.
     *
     * @param \Illuminate\Http\Request $rqst the incoming request
     * @param int                      $id   the ID of the record to update
     *
     * @return \Illuminate\Http\Response
     */
    public function putById(Request $rqst, $id)
    {
        $response = null;
        $user = Auth::user();
        $model = static::$model;
        $record = $model::find($id);
        if ($record === null) {
            abort(404, 'Record not found.');
        } elseif ($record->isUpdatableByUser($user)) {
            # TODO: unique checks
            if ($record->validate() && $record->update()) {
                $response = $record;
            } else {
                $response = ['errors' => $record->errors()->all()];
            }
        } else {
            abort(401);
        }
        return $response;
     }
}
