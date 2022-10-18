<?php

namespace App\Events;

use App\Enums\EventType;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NotificationReadEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $notification;

    public function __construct($notification)
    {
        $this->notification = $notification;
    }


    public function broadcastOn()
    {
        return new PrivateChannel('notification-read.'.$this->notification->id);
    }

    public function broadcastWith(): array
    {
        return [
            'update_type'     => EventType::EVENT_NOTIFICATION_READ,
            'notification_id' => $this->notification->id
        ];
    }
}
