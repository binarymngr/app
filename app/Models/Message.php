<?php

namespace App\Models;

final class Message extends RESTModel
{
    protected $fillable = ['title', 'body'];
    protected $visible  = ['id', 'title', 'body', 'created_at', 'binary_id', 'binary_version_id',
                           'server_id', 'user_id'];

    public static $relationsData = [
        'binary'         => [self::BELONGS_TO, 'App\Models\Binary'],
        'binary_version' => [self::BELONGS_TO, 'App\Models\BinaryVersion'],
        'server'         => [self::BELONGS_TO, 'App\Models\Server'],
        'user'           => [self::BELONGS_TO, 'App\Models\User']
    ];
    public static $rules = [
        'title' => 'required|between:1,100',
        'body'  => 'required'
    ];


    /**
     * @{inherit}
     */
    public static function getAllVisibleToUser(User $user)
    {
        $messages = null;
        if ($user->isAdmin()) {
            $messages = Message::all();
        } else {
            $messages = Message::where(
                'user_id', '=', $user->id
            )->get();
        }
        return $messages;
    }

    /**
     * @{inherit}
     */
    public function isDeletableByUser(User $user)
    {
        return $this->isVisibleToUser($user);
    }

    /**
     * Checks if this message belongs to a binary.
     *
     * @return bool true if this message references a binary
     */
    public function isForBinary()
    {
        return $this->binary !== null;
    }

    /**
     * Checks if this message belongs to a binary version.
     *
     * @return bool true if this message references a binary version
     */
    public function isForBinaryVersion()
    {
        return $this->binary_version !== null;
    }

    /**
     * Checks if this message belongs to a server.
     *
     * @return bool true if this message references a server
     */
    public function isForServer()
    {
        return $this->server !== null;
    }

    /**
     * @{inherit}
     */
    public function isUpdatableByUser(User $user)
    {
        return false;
    }

    /**
     * @{inherit}
     */
    public function isVisibleToUser(User $user)
    {
        return $user->isAdmin() || $user === $this->user;
    }
}
