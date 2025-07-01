<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class FormApprovedByReviewer
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $authUser;
    public $reviewer;
    public $data;

    /**
     * Create a new event instance.
     */
    public function __construct($authUser, $reviewer, $data)
    {
        $this->authUser = $authUser;
        $this->reviewer = $reviewer;
        $this->data = $data;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('form-status-updated'),
        ];
    }
}
