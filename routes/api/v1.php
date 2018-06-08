<?php

use Illuminate\Http\JsonResponse;

Route::get('/', function(): JsonResponse {
    return response()->json(['status' => 'success'], 200);
});

# Public routes.
Route::post('register', 'AuthController@register');
Route::post('login', 'AuthController@login');

# Protected routes.
Route::group(['middleware' => 'jwt.auth'], function() {
    Route::post('logout', 'AuthController@logout');
});