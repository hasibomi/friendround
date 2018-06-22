<?php

namespace FriendRound\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MessageSentboxResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'thread' => $this->thread_id,
            'receiver' => [
                'id' => $this->receiver_id,
                'email' => $this->receiver->email,
                'username' => $this->receiver->username,
                'name' => $this->receiver->name,
                'photo' => $this->receiver->photo
            ],
            'subject' => $this->subject,
            'body' => $this->body,
            'created' => $this->created_at->timestamp
        ];
    }
}
