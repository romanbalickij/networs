<?php


namespace App\Services\Chat;


use App\Enums\InteractionType;

class ConversationUser implements ConversationInterface
{

    public function join($room, int $recipient)
    {
        return $this->addRecipient($room, $recipient);
    }

    public function hasRoom($currentUser, $recipient = null)
    {
        return $currentUser->chatFor($recipient);
    }

    public function addRecipient($room,  int $recipient) {

        return $room->update(['service_id' => $recipient]);
    }

    public function createRoom($currentUser, $recipient = null) {

        if($room = $this->hasRoom($currentUser, $recipient)) {
            return $room;
        }

        $room = $currentUser->chats()->create(['service_id' => $recipient]);

        $room->pushToInteractions(InteractionType::TYPE_CHAT_CREATION, $currentUser);

        return $room;

    }

    public function leave($room)
    {

    }
}
