<?php

namespace FriendRound\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BlockList extends Model
{
    protected $fillable = ['user_id', 'blocked_user_id'];

    /**
     * Relationship with `users` table.
     *
     * @return BelongsTo
     */
    public function blockedUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'blocked_user_id', 'id');
    }
}
