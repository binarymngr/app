<?php namespace App\Models;

final class BinaryCategory extends RESTModel
{
    protected $fillable = ['name', 'description'];
    protected $visible  = ['id', 'name', 'description'];

    public static $relationsData = [
        'binaries' => [self::BELONGS_TO_MANY, 'App\Models\Binary', 'table' => 'binaries_categories']
    ];
    public static $rules = [
        'name' => 'required|between:1,75'  # TODO: unique
    ];


    public function delete()
    {
        $this->binaries()->detach();
        return parent::delete();
    }

    public static function getAllVisibleToUser(User $user)
    {
        return BinaryCategory::all();
    }

    public function hasBinaries()
    {
        return !$this->binaries->isEmpty();
    }

    public function isDeletableByUser(User $user)
    {
        return $this->isUpdatableByUser($user);
    }

    public function isUpdatableByUser(User $user)
    {
        return $user->isAdmin();
    }

    public function isVisibleToUser(User $user)
    {
        return true;
    }
}
