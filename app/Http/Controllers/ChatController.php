<?php

namespace App\Http\Controllers;

use App\Events\NewMessage;
use App\Models\Chat;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use TheSeer\Tokenizer\Exception;

class ChatController extends Controller
{
    public function ValidatesendMessage(Request $request)
    {
        return Validator::make($request->all() , [
            'receiver_id' => 'required|exists:users,id',
            'message' => 'required|string | max : 600',
        ]);
    }
    /**
     * Send a new chat message.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendMessage(Request $request)
    {
        try{
            $validatedData = $this->ValidatesendMessage($request) ;
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

    public function ValidategetConversation($request)
    {
        return Validator::make($request->all() ,[
            'receiver_id' => 'required|exists:users,id'
        ]) ;
    }
    /**
     * Retrieve chat messages between two users.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getConversation(Request $request)
    {
        $validatedData = $this->ValidategetConversation($request) ;
        if($validatedData->fails())
        {
            return response()->json([
                'Error' => $validatedData->errors()
            ]);
        }
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
