<?php

namespace FriendRound\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class FriendListResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'username' => $this->user->username,
            'photo' => $this->user->photo,
            'name' => $this->user->name,
            'loggedin' => $this->user->loggedin === 1 ? true : false
        ];
    }
}
