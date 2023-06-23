<?php

namespace App\Listeners;

use App\Events\NewMessage;
use App\Http\Controllers\ChatController;
use App\Models\Chat;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class NewMessageListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {

    }

    /**
     * Handle the event.
     */
    public function handle(NewMessage $event): void
    {
        $chat = $event->chat;

        Log::info('New chat message received:', [
            'message' => $chat->message,
            'sender_id' => $chat->sender_id,
            'receiver_id' => $chat->receiver_id,
        ]);
    }
}
