<?php

namespace FriendRound\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use JWTAuth;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'photo', 'username', 'email', 'password', 'loggedin'
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
     * Make the logged in user online.
     *
     * @param int $ID
     * @return void
     */
    public function setOnline(int $ID) : void
    {
        $this->where('id', $ID)->update(['loggedin' => 1]);
    }

    /**
     * Make the logged in user offline.
     *
     * @param int $ID
     * @return void
     */
    public function setOffline(int $ID) : void
    {
        $this->where('id', $ID)->update(['loggedin' => 0]);
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
     * @return \Illuminate\Database\Query\Builder
     */
    public function friends(): \Illuminate\Database\Query\Builder
    {
        $auth = JWTAuth::parseToken()->authenticate();

        return \DB::table('friends')->select('friends.*', 'users.id as userID', 'username', 'name', 'email', 'photo', 'loggedin')->join('users', function ($join) use ($auth) {
            $join->on('users.id', '=', 'friends.receiver_id')->orOn('users.id', '=', 'friends.sender_id');
        })->where('friends.status', '=', 1)
          ->where('users.id', '!=', $auth->id)
          ->where('friends.receiver_id', '=', $auth->id)
          ->orWhere('friends.sender_id', '=', $auth->id);
    }

    /**
     * Get online friends.
     *
     * @return \Illuminate\Database\Query\Builder
     */
    public function onlineFriends(): \Illuminate\Database\Query\Builder
    {
        return $this->friends()->where('loggedin', 1);
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
