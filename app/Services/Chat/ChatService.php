<?php


namespace App\Services\Chat;

class ChatService implements ConversationInterface
{

    public $conversation;

    public function __construct(ConversationInterface $conversation)
    {
        $this->conversation = $conversation;
    }

    public function join($room, int $recipient)
    {
        return $this->conversation->join($room, $recipient);
    }

    public function hasRoom($currentUser, ?int $recipient = null)
    {
        return $this->conversation->hasRoom($currentUser, $recipient);
    }

    public function addRecipient($room, int $recipient)
    {
        return $this->conversation->addRecipient($room, $recipient);
    }

    public function leave($room)
    {
        return $this->conversation->leave($room);
    }

    public function createRoom($currentUser, $recipient = null)
    {
        return $this->conversation->createRoom($currentUser, $recipient);
    }
}
