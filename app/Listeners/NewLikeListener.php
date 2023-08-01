<?php

namespace App\Listeners;

use App\Events\LikeEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class NewLikeListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(LikeEvent $event): void
    {
        Log::info('New Like received:', [
            'type' => $event->type,
            'receiver_id' => $event->receiver_id,
            'post_id' => $event->post_id,
        ]);
    }
}
