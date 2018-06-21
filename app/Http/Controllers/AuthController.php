<?php

namespace FriendRound\Http\Controllers;

use Illuminate\Http\{ Request, JsonResponse };
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use FriendRound\Http\Requests\{ LoginRequest, RegistrationRequest };
use FriendRound\Models\{ User };

class AuthController extends Controller
{
    /**
     * Handle a user registration request.
     *
     * @param RegistrationRequest $request
     * @return JsonResponse
     */
    public function register(RegistrationRequest $request) : JsonResponse
    {
        User::create($request->all());

        // Send user a mail.

        return response()->json(['status' => 'success', 'message' => 'Registration successful'], 201);
    }

    /**
     * Handle a user login request.
     *
     * @param LoginRequest $request
     * @return JsonResponse
     */
    public function login(LoginRequest $request) : JsonResponse
    {
        $credentials = $request->only('username', 'password');

        try {
            if (!$token = JWTAuth::attempt($credentials)) {
                return response()->json(['status' => 'error', 'message' => 'Invalid credentials'], 400);
            }
        } catch (JWTException $e) {
            return response()->json(['status' => 'error', 'message' => $e], 500);
        }

        $auth = JWTAuth::setToken($token)->authenticate();
        $auth->setOnline($auth->id);

        return response()->json(['status' => 'success', 'token' => $token], 200);
    }

    /**
     * Handle a user logout request.
     *
     * @return JsonResponse
     */
    public function logout() : JsonResponse
    {
        $auth = JWTAuth::parseToken()->authenticate();
        $auth->setOffline($auth->id);
        JWTAuth::invalidate(JWTAuth::getToken());

        return response()->json(['status' => 'success', 'message' => 'Logout successfull'], 200);
    }
}
