<?php

namespace App\Events;

use App\Enums\EventType;
use App\Models\Post;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PostHistoryEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $post;

    public $type;

    public function __construct(Post $post, $type)
    {
        $this->post = $post;

        $this->type = $type;
    }

    public function broadcastOn()
    {
        return new PresenceChannel('post-history.'.$this->post->id);
    }

    public function broadcastWith(): array
    {
        return [
            'update_type' => $this->type,
            'post_id'     => $this->post->id,
        ];
    }
}
