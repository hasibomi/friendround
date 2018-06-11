<?php

namespace FriendRound\Http\Controllers;

use Illuminate\Http\{ Request, JsonResponse };
use FriendRound\Http\Requests\SearchRequest;
use FriendRound\Models\User;
use FriendRound\Http\Resources\SearchResource;
use FriendRound\Http\Resources\UserResource;
use JWTAuth;
use FriendRound\Models\Friend;

class FriendController extends Controller
{
    /**
     * Search users with username or email.
     *
     * @param SearchRequest $request
     * @return JsonResponse
     */
    public function search(SearchRequest $request): JsonResponse
    {
        $results = User::where('email', 'LIKE', '%' . $request->input('term') . '%')->orWhere('username', 'LIKE', '%' . $request->input('term') . '%')->get();
        $results = SearchResource::collection($results);

        return response()->json(['status' => 'success', 'results' => $results], 200);
    }

    /**
     * View a user.
     *
     * @param string $username
     * @return JsonResponse
     */
    public function view(string $username): JsonResponse
    {
        $result = User::where('username', $username)->first();

        if (is_null($result)) {
            return response()->json(['status' => 'error', 'message' => 'The requested user could not be found'], 404);
        }

        $result = new UserResource($result);

        return response()->json(['status' => 'success', 'results' => $result], 200);
    }

    /**
     * Send a friend request to a user.
     *
     * @param string $username
     * @return JsonResponse
     */
    public function sendRequest(string $username): JsonResponse
    {
        $auth = JWTAuth::parseToken()->authenticate();
        $user = User::findByUsername($username)->first();

        if (is_null($user)) {
            return response()->json(['status' => 'error', 'message' => 'The requested user could not be found'], 404);
        }

        $auth->friendRequestSender()->create([
            'receiver_id' => $user->id
        ]);

        return response()->json(['status' => 'success', 'message' => 'Friend request sent'], 201);
    }

    /**
     * Accept the specified friend request.
     *
     * @param integer $requestID
     * @return JsonResponse
     */
    public function acceptRequest(int $requestID): JsonResponse
    {
        $auth = JWTAuth::parseToken()->authenticate();
        $request = $auth->friendRequestReceiver()->where('id', $requestID);

        if (is_null($request->first())) {
            return response()->json(['status' => 'error', 'message' => 'Invalid request'], 400);
        }

        $request->update(['status' => 1]);

        return response()->json(['status' => 'success', 'message' => 'Friend request accepted'], 200);
    }

    /**
     * Decline the specified friend request.
     *
     * @param integer $requestID
     * @return JsonResponse
     */
    public function declineRequest(int $requestID) : JsonResponse
    {
        $auth = JWTAuth::parseToken()->authenticate();
        $request = $auth->friendRequestReceiver()->where('id', $requestID);

        if (is_null($request->first())) {
            return response()->json(['status' => 'error', 'message' => 'Invalid request'], 400);
        }

        $request->delete();

        return response()->json(['status' => 'success', 'message' => 'Friend request rejected'], 200);
    }
}
