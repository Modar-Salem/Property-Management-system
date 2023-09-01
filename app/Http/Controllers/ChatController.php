<?php

namespace App\Http\Controllers;

use App\Events\NewMessage;
use App\Http\Requests\Chat\GetConversationRequest;
use App\Http\Requests\Chat\SendMessageRequest;
use App\Models\Chat;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use TheSeer\Tokenizer\Exception;

class ChatController extends Controller
{

    /**
     * Send a new chat message.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendMessage(SendMessageRequest $request)
    {
        try{

            $chat = Chat::create([
                'sender_id' => Auth::id(),
                'receiver_id' => $request['receiver_id'],
                'message' => $request['message']
            ]);


            broadcast(new NewMessage($chat));

            return response()->json([
                'Chat : ' => $chat
            ], 201);
        }catch (Exception $exception)
        {
            return  response()->json([
                'Error' => $exception->getMessage()
            ]);
        }

    }


    /**
     * Retrieve chat messages between two users.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getConversation(GetConversationRequest $request)
    {

        $sender_id = Auth::id();

        $chats = Chat::where(function ($query) use ($request, $sender_id) {
            $query->where('sender_id', $sender_id)
                ->where('receiver_id', $request['receiver_id']);
        })->orWhere(function ($query) use ($request, $sender_id) {
            $query->where('sender_id', $request['receiver_id'])
                ->where('receiver_id', $sender_id);
        })->get();

        return response()->json($chats, 200);
    }


    public function getChattedPersons()
    {
        $senderId = Auth::id() ;
        $senders = Chat::where('sender_id', $senderId)->distinct('receiver_id')->pluck('receiver_id');
        $receivers = Chat::where('receiver_id', $senderId)->distinct('sender_id')->pluck('sender_id');

        // Merge and retrieve unique values from both sender and receiver IDs
        $chattedPersons_id = $senders->merge($receivers)->unique();
        $chattedPersons= [] ;
        foreach ($chattedPersons_id as $id)
        {
            array_push($chattedPersons , User::find($id));
        }
        return response()->json([
            'status' => true ,
            'Chatted_Person :'  => $chattedPersons
        ]);
    }

}
