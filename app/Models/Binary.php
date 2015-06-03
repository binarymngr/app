<?php namespace App\Models;

final class Binary extends RESTModel
{
    protected $appends  = ['binary_category_ids', 'binary_version_ids'];
    protected $fillable = ['name', 'description', 'homepage', 'owner_id'];
    protected $visible  = ['id', 'name', 'description', 'homepage', 'owner_id',
                           'binary_category_ids', 'binary_version_ids'];

    public static $relationsData = [
        'categories' => [self::BELONGS_TO_MANY, 'App\Models\BinaryCategory', 'table' => 'binaries_categories'],
        'owner'      => [self::BELONGS_TO, 'App\Models\User', 'foreignKey' => 'owner_id'],
        'versions'   => [self::HAS_MANY, 'App\Models\BinaryVersion'],
    ];
    public static $rules = [
        'name'     => 'required|between:1,100',  # TODO: unique:binaries,name
        'homepage' => 'url|between:1,100',
        'owner_id' => 'required|exists:users,id|integer'
    ];


    /**
     * @{inherit}
     */
    public static function getAllVisibleToUser(User $user)
    {
        $binaries = null;
        if ($user->isAdmin()) {
            $binaries = Binary::all();
        } else {
            $binaries = Binary::where(
                'owner_id', '=', $user->id
            )->get();
        }
        return $binaries;
    }

    /**
     * Accessor for the virtual 'binary_category_ids' attribute.
     *
     * @link http://laravel.com/docs/5.0/eloquent#converting-to-arrays-or-json
     *
     * @return array an array containing the category IDs
     */
    public function getBinaryCategoryIdsAttribute()
    {
        $category_ids = [];
        foreach ($this->categories as $category) {
            $category_ids[] = $category->id;
        }
        return $category_ids;
    }

    /**
     * Accessor for the virtual 'binary_version_ids' attribute.
     *
     * @link http://laravel.com/docs/5.0/eloquent#converting-to-arrays-or-json
     *
     * @return array an array containing the version IDs
     */
    public function getBinaryVersionIdsAttribute()
    {
        $version_ids = [];
        foreach ($this->versions as $version) {
            $version_ids[] = $version->id;
        }
        return $version_ids;
    }

    /**
     * Returns the latest version record of this binary.
     *
     * @return \App\Models\BinaryVersion
     */
    public function getLatestVersion()
    {
        foreach ($this->versions as $version) {
            if ($version->isLatest()) {
                return $version;
            }
        }
        return null;
    }

    /**
     * Returns a collection of versions that are not the latest.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getOutdatedVersions()
    {
        return $this->versions->reject(function($version) {
            return $version->isLatest();
        });
    }

    /**
     * Checks if this binary is within a category.
     *
     * @return bool true if at least one category is associated with this binary
     */
    public function hasCategories()
    {
        return !$this->categories->isEmpty();
    }

    /**
     * Checks if versions have been added to this binary.
     *
     * @return bool true if at least one version has been added
     */
    public function hasVersions()
    {
        return !$this->versions->isEmpty();
    }

    /**
     * Checks if this binary is installed on a server.
     *
     * @return bool true if at least one server has the binary installed
     */
    public function isInstalled()
    {
        foreach ($this->versions as $version) {
            if ($version->isInstalled()) {
                return true;
            }
        }
        return false;
    }

    /**
     * @{inherit}
     */
    public function isDeletableByUser(User $user)
    {
        return $this->isUpdatableByUser($user);
    }

    /**
     * @{inherit}
     */
    public function isUpdatableByUser(User $user)
    {
        return $this->isVisibleToUser($user);
    }

    /**
     * @{inherit}
     */
    public function isVisibleToUser(User $user)
    {
        return $user->isAdmin() || $user->id === $this->owner_id;
    }
}
