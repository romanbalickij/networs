<?php

namespace App\Events;

use App\Enums\EventType;
use App\Http\Resources\User\UserResource;
use App\Models\Message;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use  Illuminate\Broadcasting\PresenceChannel;

class ReactMessageEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;


    public $message;

    public $reaction;

    public function __construct(Message $message, string $reaction)
    {
        $this->message  = $message;
        $this->reaction = $reaction;
    }

    public function broadcastOn()
    {
       // return new PresenceChannel('conversation.'.$this->message->chat_id);

        return new PresenceChannel('conversation');
    }

    public function broadcastWith(): array
    {
        return [
            'update_type' => EventType::EVENT_REACTION_MESSAGE,
            'message_id'  => $this->message->id,
            'reaction'    => $this->reaction,
            'sender'      => (new UserResource(user()))->only('id', 'name', 'surname', 'avatar', 'nickname')
        ];
    }

}
