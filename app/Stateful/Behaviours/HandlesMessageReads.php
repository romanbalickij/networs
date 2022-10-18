<?php


namespace App\Stateful\Behaviours;


use App\Enums\EventType;
use App\Models\Message;
use App\Services\Actions\MessageReadAction;
use App\Stateful\Behaviour;
use App\Stateful\EventProtocol;
use Illuminate\Support\Arr;

/**
 * Class HandlesMessageReads
 * @package App\Stateful\Behaviours
 *
 *
 * Request:
 * {
 *   message_id: 12345
 * }
 *
 */
class HandlesMessageReads extends Behaviour
{

    public function on (): array
    {
        return [EventType::EVENT_FROM_FRONTEND_SEND_MESSAGE_READ];
    }

     /**
      * Models Messages  id
      **/
    public function triggered (\App\Stateful\EventProtocol $protocol)
    {
        $message = Message::findOrFail($protocol->getPayload()['id']);
        $user = $protocol->getSenderConnection()->getUser();

        if (!in_array($user->id, [$message->chat->service_id, $message->chat->client_id]))
            throw new \Exception('This user does not own this message');

        tap($message)->read();

        $this->broadcast(new EventProtocol($message->getOtherUser($user->id), EventType::EVENT_FROM_FRONTEND_SEND_MESSAGE_READ,  [
            'message_id' => $message->id
        ]));
    }
}
