<?php

namespace FriendRound\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Group extends Model
{
    protected $fillable = ['user_id', 'name', 'slug', 'description', 'photo'];

    /**
     * Relationship with `users` table.
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
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
