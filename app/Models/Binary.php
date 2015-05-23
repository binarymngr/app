<?php namespace App\Models;

final class Binary extends RESTModel
{
    protected $fillable = ['name', 'description', 'homepage', 'owner_id'];
    protected $visible  = ['id', 'name', 'description', 'homepage', 'owner_id'];

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
     *
     * @Override to detach the categories and delete the versions
     */
    public function delete()
    {
        $this->categories()->detach();
        $this->versions()->delete();
        return parent::delete();
    }

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
     * Checks if this binary is within a category.
     *
     * @return Bool true if at least one category is associated with this binary
     */
    public function hasCategories()
    {
        return !$this->categories->isEmpty();
    }

    /**
     * Checks if versions have been added to this binary.
     *
     * @return Bool true if at least one version has been added
     */
    public function hasVersions()
    {
        return !$this->versions->isEmpty();
    }

    /**
     * Checks if this binary is installed on a server.
     *
     * @return Bool true if at least one server has the binary installed
     */
    public function isInstalled()
    {
        $installed = false;
        $this->versions->each(function($version) use ($installed) {
            if ($version->isInstalled()) {
                $installed = true;
                break;
            }
        });
        return $installed;
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