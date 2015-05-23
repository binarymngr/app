<?php namespace App\Models;

final class BinaryVersion extends RESTModel
{
    protected $dates    = ['eol', 'created_at', 'updated_at'];
    protected $fillable = ['identifier', 'note', 'eol', 'binary_id'];
    protected $visible  = ['id', 'identifier', 'note', 'eol', 'binary_id'];

    public static $relationsData = [
        'binary'  => [self::BELONGS_TO, 'App\Models\Binary', 'table' => 'binaries'],
        'servers' => [self::BELONGS_TO_MANY, 'App\Models\Server', 'table' => 'servers_binary_versions']
    ];
    public static $rules = [
        'identifier' => 'required|between:1,64',
        'eol'        => 'date',
        'binary_id'  => 'required|exists:binaries,id|integer'
    ];


    /**
     * @{inherit}
     *
     * @Override to detach the servers before deletion
     */
    public function delete()
    {
        $this->servers()->detach();
        return parent::delete();
    }

    /**
     * @{inherit}
     */
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

    /**
     * Checks if this version is installed on a server.
     *
     * @return Bool true if at least one server has this version installed
     */
    public function isInstalled()
    {
        return !$this->servers->isEmpty();
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
        return $this->binary->isVisibleToUser($user);
    }
}
