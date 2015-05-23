<?php namespace App;

use App\User;
use LaravelBook\Ardent\Ardent;

final class Server extends Ardent
{
    public $autoHydrateEntityFromInput    = true;
    public $forceEntityHydrationFromInput = true;

    protected $dates     = ['created_at', 'updated_at'];
    protected $fillable  = ['name', 'ipv4', 'owner_id'];
    protected $visible   = ['id', 'name', 'ipv4', 'owner_id'];

    public static $relationsData = [
        'binaries' => [self::BELONGS_TO_MANY, 'App\BinaryVersion', 'table' => 'servers_binary_versions'],
        'owner'    => [self::BELONGS_TO, 'App\User', 'foreignKey' => 'owner_id']
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

    public function isVisibleToUser(User $user)
    {
        return $user->isAdmin() || $user->id === $this->owner_id;
    }
}
