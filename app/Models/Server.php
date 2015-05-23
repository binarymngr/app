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


    public function delete()
    {
        $this->binaries()->detach();
        return parent::delete();
    }

    public function hasBinaries()
    {
        return !$this->binaries->isEmpty();
    }

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
