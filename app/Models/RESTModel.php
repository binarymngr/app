<?php namespace App\Models;

use LaravelBook\Ardent\Ardent;

abstract class RESTModel extends Ardent
{
    public $autoHydrateEntityFromInput    = true;
    public $forceEntityHydrationFromInput = true;

    protected $dates = ['created_at', 'updated_at'];


    public static function getAllVisibleToUser(User $user)
    {
        $visibles = static::all();
        $visibles = $visibles->reject(function($visible) use ($user) {
            return !$visible->isVisibleToUser($user);
        })->flatten();
        return $visibles;
    }

    abstract public function isDeletableByUser(User $user);
    abstract public function isUpdatableByUser(User $user);
    abstract public function isVisibleToUser(User $user);
}
