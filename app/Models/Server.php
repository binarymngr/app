<?php namespace App\Models;

final class Server extends RESTModel
{
    protected $fillable = ['name', 'ipv4', 'owner_id'];
    protected $visible  = ['id', 'name', 'ipv4', 'owner_id'];

    public static $relationsData = [
        'binaries' => [self::BELONGS_TO_MANY, 'App\Models\BinaryVersion', 'table' => 'servers_binary_versions'],
        'owner'    => [self::BELONGS_TO, 'App\Models\User', 'foreignKey' => 'owner_id']
    ];
    public static $rules = [
        'name'     => 'required|between:1,75',  # TODO: unique:servers,name
        'ipv4'     => 'required|ip|between:7,15',  # TODO: unique:server,ipv4
        'owner_id' => 'required|exists:users,id|integer'
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
     * Checks if binaries are installed on this server.
     *
     * @return Bool true if at least one binary (version) is installed
     */
    public function hasBinariesInstalled()
    {
        return !$this->binaries->isEmpty();
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
