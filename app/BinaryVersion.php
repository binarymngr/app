<?php namespace App;

use LaravelBook\Ardent\Ardent;

final class BinaryVersion extends Ardent
{
    public $autoHydrateEntityFromInput    = true;
    public $forceEntityHydrationFromInput = true;

    protected $dates    = ['eol', 'created_at', 'updated_at'];
    protected $fillable = ['identifier', 'note', 'eol', 'binary_id'];
    protected $visible  = ['id', 'identifier', 'note', 'eol', 'binary_id'];

    public static $relationsData = [
        'binary'  => [self::BELONGS_TO, 'App\Binary', 'table' => 'binaries'],
        'servers' => [self::BELONGS_TO_MANY, 'App\Server', 'table' => 'servers_binary_versions']
    ];
    public static $rules = [
        'identifier' => 'required|between:1,64',
        'eol'        => 'date',
        'binary_id'  => 'required|exists:binaries,id|integer'
    ];



    public function delete()
    {
        $this->servers()->detach();
        return parent::delete();
    }

    public static function getAllVisibleToUser(User $user)
    {
        $versions = BinaryVersion::all();
        if (!$user->isAdmin()) {
            $versions = $versions->reject(function($version) use ($user) {
                return !$version->isVisibleToUser($user);
            })->flatten();
        }
        return $versions;
    }

    public function isInstalled()
    {
        return !$this->servers->isEmpty();
    }

    public function isVisibleToUser(User $user)
    {
        return $this->binary->isVisibleToUser($user);
    }
}
