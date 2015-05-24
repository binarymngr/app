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


    /**
     * @{inherit}
     *
     * @Override to detach the binaries before deletion
     */
    public function delete()
    {
        $this->binaries()->detach();
        return parent::delete();
    }

    /**
     * @{inherit}
     */
    public static function getAllVisibleToUser(User $user)
    {
        return BinaryCategory::all();
    }

    /**
     * Checks if a binary belongs to this category.
     *
     * @return Bool true if at least one binary is in this category
     */
    public function hasBinaries()
    {
        return !$this->binaries->isEmpty();
    }

    /**
     * @{inherit}
     */
    public function isDeletableByUser(User $user)
    {
        return $user->isAdmin();
    }

    /**
     * @{inherit}
     */
    public function isUpdatableByUser(User $user)
    {
        return $user->isAdmin();
    }

    /**
     * @{inherit}
     */
    public function isVisibleToUser(User $user)
    {
        return true;
    }
}
