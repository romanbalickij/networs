<?php


namespace App\Services\Chat;


use App\Models\Chat;

interface ConversationInterface
{

    public function join(Chat $room, int $recipient);
    public function addRecipient(Chat $room, int $recipient);
    public function leave(Chat $room);
    public function createRoom($currentUser, $recipient = null);
    public function hasRoom($currentUser, ?int $recipient = null);
}
