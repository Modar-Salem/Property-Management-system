<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use Illuminate\Http\Request;
use Pusher\Pusher;

class ChatController extends Controller
{
    /**
     * Send a new chat message.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendMessage(Request $request)
    {
        $validatedData = $request->validate([
            'sender_id' => 'required|exists:users,id',
            'receiver_id' => 'required|exists:users,id',
            'message' => 'required|string',
        ]);

        $chat = Chat::create($validatedData);


        // Broadcast event to Pusher
        $pusher = new Pusher(
            env('PUSHER_APP_KEY'),
            env('PUSHER_APP_SECRET'),
            env('PUSHER_APP_ID'),
            [
                'cluster' => env('PUSHER_APP_CLUSTER'),
                'encrypted' => true, // Set to true if your app is using HTTPS
            ]
        );

        $pusher->trigger('chat-channel', 'new-message', $chat);

        return response()->json($chat, 201);
    }

    /**
     * Retrieve chat messages between two users.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getConversation(Request $request)
    {
        $validatedData = $request->validate([
            'sender_id' => 'required|exists:users,id',
            'receiver_id' => 'required|exists:users,id',
        ]);

        $chats = Chat::where(function ($query) use ($validatedData) {
            $query->where('sender_id', $validatedData['sender_id'])
                ->where('receiver_id', $validatedData['receiver_id']);
        })->orWhere(function ($query) use ($validatedData) {
            $query->where('sender_id', $validatedData['receiver_id'])
                ->where('receiver_id', $validatedData['sender_id']);
        })->get();

        return response()->json($chats, 200);
    }

}
