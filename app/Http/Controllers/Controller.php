<?php

namespace FriendRound\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use FriendRound\Models\User;
use JWTAuth;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * Get the authenticated user from token.
     *
     * @return User
     */
    protected function auth(): User
    {
        return JWTAuth::parseToken()->authenticate();
    }
}
