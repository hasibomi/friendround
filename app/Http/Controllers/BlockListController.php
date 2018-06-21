<?php

namespace FriendRound\Http\Controllers;

use Illuminate\Http\{ Request, JsonResponse };
use FriendRound\Http\Resources\BlockListResource;
use FriendRound\Models\Friend;

class BlockListController extends Controller
{
    /**
     * Get all blocked users list.
     *
     * @return JsonResponse
     */
    public function list(): JsonResponse
    {
        $results = BlockListResource::collection($this->auth()->blockList);

        return response()->json(['status' => 'success', 'results' => $results], 200);
    }

    /**
     * Block a user.
     *
     * @param integer $userID
     * @return JsonResponse
     */
    public function store(int $userID): JsonResponse
    {
        # Remove the user from friend list first.
        $this->auth()->friends()->orWhere('sender_id', $userID)->orWhere('receiver_id', $userID)->delete();

        # Now block the user.
        $this->auth()->blockList()->create([
            'blocked_user_id' => $userID
        ]);

        return response()->json(['status' => 'success', 'message' => 'Block successful'], 201);
    }

    /**
     * Unblock a user.
     *
     * @param integer $blockListID
     * @return JsonResponse
     */
    public function destroy(int $blockListID): JsonResponse
    {
        $this->auth()->blockList()->where('id', $blockListID)->delete();

        return response()->json(['status' => 'success', 'message' => 'Unblock successful'], 200);
    }
}
