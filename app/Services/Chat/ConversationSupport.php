<?php


namespace App\Services\Chat;

use App\Enums\ChatType;
use App\Enums\MessageType;

class ConversationSupport implements ConversationInterface
{

    public function join($room, int $admin) {

        $room->messages()->create([
            'user_id' => $room->client_id,
            'meta'    => MessageType::ADMIN_ENTERED
        ]);

       return $this->addRecipient($room, $admin);
    }

    public function hasRoom($currentUser, ?int $admin = null)
    {
        return $currentUser->support()->exists();
    }

    public function addRecipient($room, int $admin)
    {
        return tap($room)->update(['service_id' => $admin]);
    }

    public function createRoom($user, $recipient = null) {

       return $user
           ->support()
           ->create(['mode' => ChatType::ADMIN, 'service_id' => $recipient]);
    }

    public function leave($room)
    {
        $room->update(['service_id' => null]);

        $room->messages()->create([
           'user_id' => $room->client_id,
           'meta'    => MessageType::ADMIN_LEFT
        ]);
    }
}
