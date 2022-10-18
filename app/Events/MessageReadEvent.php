<?php

namespace App\Events;

use App\Enums\EventType;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PresenceChannel;

class MessageReadEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;

    public function __construct($message)
    {
        $this->message = $message;
    }

    public function broadcastOn()
    {
      //  return new PresenceChannel('conversation.'.$this->message->chat_id);

        return new PresenceChannel('conversation');
    }

    public function broadcastWith(): array
    {
        return [
            'update_type' => EventType::EVENT_MARK_MESSAGE_READ,
            'message_id' => $this->message->id
        ];
    }

}
