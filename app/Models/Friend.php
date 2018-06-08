<?php

namespace FriendRound\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Friend extends Model
{
    protected $fillable = ['sender_id', 'receiver_id', 'status'];

    /**
     * Relationship with `users` table in terms of sender of friend request.
     *
     * @return BelongsTo
     */
    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sender_id', 'id');
    }

    /**
     * Relationship with `users` table in terms of receiver of friend request.
     *
     * @return BelongsTo
     */
    public function receiver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'receiver_id', 'id');
    }
}
