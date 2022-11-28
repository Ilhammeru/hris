<?php

namespace Modules\Recruitment\Events;

use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;

class MessageFromVacancy implements ShouldBroadcast
{
    use SerializesModels;

    public $count;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($count)
    {
        $this->count = $count;
    }

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
        return ['message-from-vacancy'];
    }
}
