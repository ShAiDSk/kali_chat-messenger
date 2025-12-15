<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow; // <--- IMPORTANT
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageRead implements ShouldBroadcastNow // <--- MUST IMPLEMENT THIS
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $messageIds;
    public $receiverId;

    public function __construct($messageIds, $receiverId)
    {
        $this->messageIds = $messageIds;
        $this->receiverId = $receiverId;
    }

    public function broadcastOn()
    {
        // We broadcast to the user who SENT the messages (so they see blue ticks)
        return new PrivateChannel('chat.' . $this->receiverId);
    }
}