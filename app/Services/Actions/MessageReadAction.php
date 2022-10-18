<?php


namespace App\Services\Actions;

use App\Enums\EventType;
use App\Models\Message;

class MessageReadAction
{

    public function execute(Message $message) {

        tap($message)->read();

        app(EventMessageAction::class)->handler($message->chat, EventType::EVENT_MARK_MESSAGE_READ, ['message_id' => $message->id]);
    }
}
