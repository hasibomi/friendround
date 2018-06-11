<?php

namespace FriendRound\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'photo', 'username', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * Save hashed password in to database.
     *
     * @param string $value
     * @return void
     */
    public function setPasswordAttribute(string $value): void
    {
        $this->attributes['password'] = bcrypt($value);
    }

    /**
     * Find a user by username.
     *
     * @param string $username
     * @return self
     */
    public static function findByUsername(string $username): \Illuminate\Database\Eloquent\Builder
    {
        return self::where('username', $username);
    }

    /**
     * Relationship with `friends` table in terms of friend request sender.
     *
     * @return HasMany
     */
    public function friendRequestSender(): HasMany
    {
        return $this->hasMany(Friend::class, 'sender_id', 'id');
    }

    /**
     * Relationship with `friends` table in terms of friend request receiver.
     *
     * @return HasMany
     */
    public function friendRequestReceiver(): HasMany
    {
        return $this->hasMany(Friend::class, 'receiver_id', 'id');
    }

    /**
     * Get friend list.
     *
     * @return Friend
     */
    public function friend(): Friend
    {
        return Friend::where('sender_id', $this->id)->orWhere('receiver_id', $this->id)->where('status', 1)->get();
    }

    /**
     * Relationship with `block_lists` table.
     *
     * @return HasMany
     */
    public function blockList(): HasMany
    {
        return $this->hasMany(BlockList::class);
    }

    /**
     * Relationship with `groups` table.
     *
     * @return HasMany
     */
    public function group(): HasMany
    {
        return $this->hasMany(Group::class);
    }

    /**
     * Relationship with `members` table.
     *
     * @return HasMany
     */
    public function member(): HasMany
    {
        return $this->hasMany(Member::class);
    }
}
