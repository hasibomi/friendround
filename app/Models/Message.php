<?php

namespace FriendRound\Models;

use Illuminate\Database\Eloquent\{ Model, Relations\BelongsTo };
use Illuminate\Database\Eloquent\Builder;

class Message extends Model
{
    protected $fillable = ['conversation_id', 'sender_id', 'receiver_id', 'body', 'new'];

    /**
     * Check conversations between the users.
     *
     * @param array $users
     * @return Builder
     */
    public static function hasCoversationBetween(array $users): Builder
    {
        return self::where('sender_id', $users[0])->where('receiver_id', $users[1])
                    ->orWhere('sender_id', $users[1])->orWhere('receiver_id', $users[0]);
    }

    /**
     * Find a conversation by conversation ID.
     *
     * @param string $conversationID
     * @return Builder
     */
    public static function findByConversationID(string $conversationID): Builder
    {
        return self::where('conversation_id', $conversationID);
    }

    /**
     * Relationship with `users` table in terms of sender.
     *
     * @return BelongsTo
     */
    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sender_id', 'id');
    }

    /**
     * Relationship with `users` table in terms of receiver.
     *
     * @return BelongsTo
     */
    public function receiver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'receiver_id', 'id');
    }
}
