<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CommentAdded implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;
    public $authorId;

    public function __construct($authorId) {
        $this->authorId = $authorId;
        $this->message = "Someone Commented On Your Post";
    }

    public function broadcastOn()
    {
        return ['technet-chronicles'];
    }

    public function broadcastAs() {
        return 'comment-on-post';
    }
}