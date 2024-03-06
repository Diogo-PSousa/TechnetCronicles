<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class VoteOnContent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;
    public $vote;
    public $content;
    public $authorId;

    public function __construct($vote,$content,$authorId) {
        $this->authorId = $authorId;
        $this->vote = $vote;
        $this->content = $content;
        $this->message = "Someone " . $vote . " Your " . $content;
    }

    public function broadcastOn()
    {
        return ['technet-chronicles'];
    }

    public function broadcastAs() {
        return 'vote-on-content';
    }
}