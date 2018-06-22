<?php

namespace FriendRound\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use JWTAuth;

class MessageInboxResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request): array
    {
        $auth = JWTAuth::parseToken()->authenticate();

        return [
            'conversationID' => $this->conversation_id,
            'name' => $this->sender_id === $auth->id ? $this->receiver->name : $this->sender->name,
            'username' => $this->sender_id === $auth->id ? $this->receiver->username : $this->sender->username,
            'created' => $this->created_at->timestamp
        ];
    }
}