<?php

namespace App\Http\Controllers;

use App\Events\NewMessage;
use App\Models\Chat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Pusher\Pusher;
use TheSeer\Tokenizer\Exception;

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

        try{
            $validatedData = Validator::make($request->all() , [

                'receiver_id' => 'required|exists:users,id',
                'message' => 'required|string',
            ]);
            if($validatedData->fails())
            {
                return response()->json([
                    'Error' => $validatedData->errors()
                ]);
            }


            $chat = Chat::create([
                'sender_id' => Auth::id(),
                'receiver_id' => $request['receiver_id'],
                'message' => $request['message']
            ]);


            event(new NewMessage($chat));

            return response()->json($chat, 201);
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
