<?php namespace App\Models;

final class Message extends RESTModel
{
    protected $fillable = ['title', 'body'];
    protected $visible  = ['id', 'title', 'body', 'created_at', 'user_id'];

    public static $relationsData = [
        'user' => [self::BELONGS_TO, 'App\Models\User']
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
