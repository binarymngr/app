<?php namespace App\Models;

final class Server extends RESTModel
{
    protected $appends  = ['binary_version_ids'];
    protected $fillable = ['name', 'ipv4', 'owner_id'];
    protected $visible  = ['id', 'name', 'ipv4', 'owner_id', 'binary_version_ids'];

    public static $relationsData = [
        'binary_versions' => [self::BELONGS_TO_MANY, 'App\Models\BinaryVersion', 'table' => 'servers_binary_versions'],
        'owner'           => [self::BELONGS_TO, 'App\Models\User', 'foreignKey' => 'owner_id']
    ];
    public static $rules = [
        'name'     => 'required|between:1,75',  # TODO: unique:servers,name
        'ipv4'     => 'required|ip|between:7,15',  # TODO: unique:server,ipv4
        'owner_id' => 'required|exists:users,id|integer'
    ];


    /**
     * @{inherit}
     */
    public static function boot()
    {
        parent::boot();
        Server::deleting(function(Server $server)
        {
            $server->binary_version()->detach();
        });
    }

    /**
     * Checks if binaries are installed on this server.
     *
     * @return Bool true if at least one binary (version) is installed
     */
    public function hasBinariesInstalled()
    {
        return !$this->binary_versions()->isEmpty();  # TODO: $this->binary_version
    }

    /**
     * Checks if this server has binaries installed that are not up-to-date.
     *
     * @return Bool true if at least one binary is not the latest version
     */
    public function hasOutdatedBinaryVersionsInstalled()
    {
        foreach ($this->binary_versions()->get() as $version) {  # TODO: $this->binary_version
            if (!$version->isLatest()) {
                return true;
            }
        }
        return false;
    }

    /**
     * @{inherit}
     *
     * @Override to detach the groups before deletion
     */
    public static function getAllVisibleToUser(User $user)
    {
        $servers = null;
        if ($user->isAdmin()) {
            $servers = Server::all();
        } else {
            $servers = Server::where(
                'owner_id', '=', $user->id
            )->get();
        }
        return $servers;
    }

    /**
     * Accessor for the virtual 'binary_ids' attribute.
     *
     * @link http://laravel.com/docs/5.0/eloquent#converting-to-arrays-or-json
     *
     * @return array an array containing the binary IDs
     */
    public function getBinaryVersionIdsAttribute()
    {
        $binary_version_ids = [];
        foreach ($this->binary_versions()->get() as $binary_version) {  # TODO: $this->binary_version
            $binary_version_ids[] = $binary_version->id;
        }
        return $binary_version_ids;
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
