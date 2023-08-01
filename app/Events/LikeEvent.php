<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class LikeEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $type ;
    public $receiver_id ;
    public $post_id ;
    /**
     * Create a new event instance.
     */
    public function __construct($type , $receiver_id , $post_id )
    {
        $this->type = $type  ;
        $this->receiver_id = $receiver_id ;
        $this->post_id = $post_id  ;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('like'.$this->receiver_id),
        ];
    }
    public function broadcastAs()
    {
        return 'like' ;
    }
}
