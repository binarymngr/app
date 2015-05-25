<?php namespace App\Models;

final class BinaryCategory extends RESTModel
{
    protected $appends  = ['binary_ids'];
    protected $fillable = ['name', 'description'];
    protected $visible  = ['id', 'name', 'description', 'binary_ids'];

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
     * Accessor for the virtual 'binary_ids' attribute.
     *
     * @link http://laravel.com/docs/5.0/eloquent#converting-to-arrays-or-json
     *
     * @return array an array containing the binary IDs
     */
    public function getBinaryIdsAttribute()
    {
        $binary_ids = [];
        foreach ($this->binaries as $binary) {
            $binary_ids[] = $binary->id;
        }
        return $binary_ids;
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
