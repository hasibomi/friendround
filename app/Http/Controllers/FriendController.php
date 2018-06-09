<?php

namespace FriendRound\Http\Controllers;

use Illuminate\Http\{ Request, JsonResponse };
use FriendRound\Http\Requests\SearchRequest;
use FriendRound\Models\User;
use FriendRound\Http\Resources\SearchResource;
use FriendRound\Http\Resources\UserResource;

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
}
