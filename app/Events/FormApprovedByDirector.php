<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class FormApprovedByDirector
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $authUser;
    public $director;
    public $data;

    /**
     * Create a new event instance.
     */
    public function __construct($authUser, $director, $data)
    {
        $this->authUser = $authUser;
        $this->director = $director;
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
