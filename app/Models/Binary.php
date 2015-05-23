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


    public function delete()
    {
        $this->categories()->detach();
        $this->versions()->delete();
        return parent::delete();
    }

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

    public function hasCategories()
    {
        return !$this->categories->isEmpty();
    }

    public function hasVersions()
    {
        return !$this->versions->isEmpty();
    }

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

    public function isDeletableByUser(User $user)
    {
        return $this->isUpdatableByUser($user);
    }

    public function isUpdatableByUser(User $user)
    {
        return $this->isVisibleToUser($user);
    }

    public function isVisibleToUser(User $user)
    {
        return $user->isAdmin() || $user->id === $this->owner_id;
    }
}
