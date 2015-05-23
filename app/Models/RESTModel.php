<?php namespace App\Models;

use LaravelBook\Ardent\Ardent;

/**
 * Base class for all models that are available via the REST API.
 *
 * @abstract
 */
abstract class RESTModel extends Ardent
{
    /*
     | Ardent properties to auto hydrate model fields from the Request.
     */
    public $autoHydrateEntityFromInput    = true;
    public $forceEntityHydrationFromInput = true;

    /*
     | Eloquent date type field marker.
     */
    protected $dates = ['created_at', 'updated_at'];


    /**
     * Returns a collection of all records the user can see.
     *
     * This method is used in combination the the user roles relation,
     * where not every user has permissions to see all records.
     *
     * @param \App\Models\User $user the user for which the visible records
     *                               should be returned
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getAllVisibleToUser(User $user)
    {
        $visibles = static::all();
        $visibles = $visibles->reject(function($visible) use ($user) {
            return !$visible->isVisibleToUser($user);
        })->flatten();
        return $visibles;
    }

    /**
     * Checks if the user is allowed to delete this record.
     *
     * @abstract
     *
     * @param \App\Models\User $user the user to check
     *
     * @return Bool true if the user can delete this record
     */
    abstract public function isDeletableByUser(User $user);

    /**
     * Checks if the user is allowed to update this record.
     *
     * @abstract
     *
     * @param \App\Models\User $user the user to check
     *
     * @return Bool true if the user can update this record
     */
    abstract public function isUpdatableByUser(User $user);

    /**
     * Checks if the user is allowed to see this record.
     *
     * @abstract
     *
     * @param \App\Models\User $user the user to check
     *
     * @return Bool true if the record is visible to the user
     */
    abstract public function isVisibleToUser(User $user);
}
