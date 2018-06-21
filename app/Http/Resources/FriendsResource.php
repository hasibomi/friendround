<?php

namespace FriendRound\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class FriendsResource extends JsonResource
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
            'id' => $this->userID,
            'username' => $this->username,
            'photo' => $this->photo,
            'name' => $this->name,
            'loggedin' => !!$this->loggedin
        ];
    }
}
