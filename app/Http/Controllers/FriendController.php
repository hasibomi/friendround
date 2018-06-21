<?php

namespace FriendRound\Http\Controllers;

use Illuminate\Http\{ Request, JsonResponse };
use FriendRound\Http\Requests\SearchRequest;
use FriendRound\Models\{ User, Friend };
use FriendRound\Http\Resources\{ SearchResource, UserResource, FriendsResource };
use JWTAuth;

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
        # Do not include blocked user in search results.
        $results = \DB::table('users')->join('block_lists', function ($join) {
            $join->on('users.id', '!=', 'block_lists.blocked_user_id')->where('user_id', $this->auth()->id);
        })->where('email', 'LIKE', '%' . $request->input('term') . '%')->orWhere('username', 'LIKE', '%' . $request->input('term') . '%')->get();
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
        $user = User::findByUsername($username)->first();

        if (is_null($user)) {
            return response()->json(['status' => 'error', 'message' => 'The requested user could not be found'], 404);
        }

        # Check the requested user is a blocked user of the authenticated user.
        $blockedUser = $this->auth()->blockList()->where('blocked_user_id', $user->id)->first();
        if (! is_null($blockedUser)) {
            return response()->json(['status' => 'error', 'message' => 'Sorry, you cannot add a blocked user as a friend'], 400);
        }

        $this->auth()->friendRequestSender()->create([
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

    /**
     * Get friend list either online or all.
     * Online friends can be filtered by using `status` query parameter.
     * Ex: /friends?status=online
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function list(Request $request): JsonResponse
    {
        $auth = JWTAuth::parseToken()->authenticate();
        if ($request->has('status') && $request->input('status') === 'online') {
            $friends = $auth->onlineFriends()->get();
        } else {
            $friends = $auth->friends()->get();
        }

        $results = FriendsResource::collection($friends);
        
        return response()->json(['status' => 'success', 'results' => $results]);
    }
}
