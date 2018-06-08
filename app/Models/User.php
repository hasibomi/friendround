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
