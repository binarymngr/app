<?php namespace App\Http\Controllers;

use App\Http\Helpers\RestrictedDeletable;
use App\Http\Helpers\RestrictedUpdatable;
use App\Http\Helpers\UserDependentGetAll;

final class BinaryCategoryController extends RESTController
{
    use RestrictedDeletable, RestrictedUpdatable, UserDependentGetAll;


    protected static $model = 'App\Models\BinaryCategory';
}
