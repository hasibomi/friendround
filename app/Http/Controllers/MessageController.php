<?php

namespace FriendRound\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use FriendRound\Http\Resources\MessageInboxResource;
use FriendRound\Http\Resources\MessageSentboxResource;
use FriendRound\Http\Requests\MessageSendRequest;
use FriendRound\Models\Message;

class MessageController extends Controller
{
    /**
     * Get all conversations of the logged in user.
     *
     * @return JsonResponse
     */
    public function conversations(): JsonResponse
    {
        $conversations = Message::where('sender_id', $this->auth()->id)->orWhere('receiver_id', $this->auth()->id)->get();
        $results = MessageInboxResource::collection($conversations);

        return response()->json(['status' => 'success', 'results' => $results]);
    }

    /**
     * Send a message to a user.
     *
     * @param MessageSendRequest $request
     * @return JsonResponse
     */
    public function send(MessageSendRequest $request): JsonResponse
    {
        # Check if there are any existing conversations between the users & get the conversation ID.
        $conversations = Message::hasCoversationBetween([$this->auth()->id, $request->receiverID]);

        if (is_null($conversations->first())) {
            $conversationID = bin2hex(random_bytes(6));
        } else {
            $conversationID = $conversations->conversation_id;
            $conversations->update(['new' => 0]);
        }

        # Finally send a message.
        $this->auth()->sentbox()->create([
            'conversation_id' => $conversationID,
            'receiver_id' => $request->receiverID,
            'body' => $request->body
        ]);

        return response()->json(['status' => 'success', 'message' => 'Message sent'], 201);
    }

    /**
     * Show the specified conversation.
     *
     * @param string $conversationID
     * @return JsonResponse
     */
    public function show(string $conversationID): JsonResponse
    {
        $conversations = Message::findByConversationID($conversationID)->get();

        return response()->json(['status' => 'success', 'results' => $conversations]);
    }
}
