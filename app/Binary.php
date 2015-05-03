<?php namespace App;

use LaravelBook\Ardent\Ardent;

final class Binary extends Ardent
{
    public $autoHydrateEntityFromInput    = true;
    public $forceEntityHydrationFromInput = true;

    protected $dates    = ['created_at', 'updated_at'];
    protected $fillable = ['name', 'description', 'homepage', 'owner_id'];
    protected $visible  = ['id', 'name', 'description', 'homepage', 'owner_id'];

    public static $relationsData = [
        'categories' => [self::BELONGS_TO_MANY, 'App\BinaryCategory', 'table' => 'binaries_categories'],
        'owner'      => [self::BELONGS_TO, 'App\User', 'foreignKey' => 'owner_id'],
        'versions'   => [self::HAS_MANY, 'App\BinaryVersion'],
    ];
    public static $rules = [
        'name'     => 'required|between:1,100',  # TODO: unique:binaries,name
        'homepage' => 'url|between:1,100',
        'owner_id' => 'required|exists:users,id|integer'
    ];


    /**
     * @Override (to detach relations)
     */
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

    public function isVisibleToUser(User $user)
    {
        return $user->isAdmin() || $user->id === $this->owner_id;
    }
}
