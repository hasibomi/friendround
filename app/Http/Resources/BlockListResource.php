<?php

namespace FriendRound\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BlockListResource extends JsonResource
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
            'id' => $this->id,
            'userID' => $this->blocked_user_id,
            'name' => $this->blockedUser->name
        ];
    }
}
