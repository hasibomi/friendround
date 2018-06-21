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

    Route::group(['middleware' => 'jwt.refresh'], function() {
        Route::get('search', 'FriendController@search');
        Route::get('users/{username}', 'FriendController@view');

        Route::group(['prefix' => 'friendrequests'], function() {
            Route::post('send/{username}', 'FriendController@sendRequest');
            Route::post('{requestID}', 'FriendController@acceptRequest');
            Route::delete('{requestID}', 'FriendController@declineRequest');
        });

        Route::get('friends', 'FriendController@list');

        # Block list.
        Route::group(['prefix' => 'blocklist'], function () {
            Route::get('/', 'BlockListController@list');
            Route::post('{userID}', 'BlockListController@store');
            Route::delete('{blockListID}', 'BlockListController@destroy');
        });
    });
});