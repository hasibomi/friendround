<?php

namespace FriendRound\Http\Middleware;

use Closure;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Middleware\BaseMiddleware;

class GetUserFromToken extends BaseMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (! $token = $this->auth->setRequest($request)->getToken()) {
            return $this->response->json([
                'status' => 'error',
                'message' => 'Token not found'
            ], 400);
        }

        try {
            $user = $this->auth->authenticate($token);
        } catch (TokenExpiredException $e) {
            return $this->response->json([
                'status' => 'error',
                'message' => 'Token has expired'
            ], $e->getStatusCode());
        } catch (JWTException $e) {
            return $this->response->json([
                'status' => 'error',
                'message' => 'Token is invalid'
            ], $e->getStatusCode());
        }

        if (! $user) {
            return $this->response->json([
                'status' => 'error',
                'message' => 'User not found'
            ], 404);
        }

        $this->events->fire('tymon.jwt.valid', $user);

        return $next($request);
    }
}
