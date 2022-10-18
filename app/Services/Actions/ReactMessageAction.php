<?php


namespace App\Services\Actions;

use App\Enums\InteractionType;
use App\Models\Message;

class ReactMessageAction
{

    public function execute(Message $message, $reactions):Message {

        $message->addReaction($reactions);

        $message->pushToInteractions(InteractionType::TYPE_REACTION, user());

        return $message;
    }
}
