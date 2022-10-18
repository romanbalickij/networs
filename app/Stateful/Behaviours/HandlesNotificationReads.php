<?php


namespace App\Stateful\Behaviours;


use App\Enums\EventType;
use App\Models\Message;
use App\Models\Notification;
use App\Services\Actions\MessageReadAction;
use App\Stateful\Behaviour;
use App\Stateful\EventProtocol;
use Illuminate\Support\Arr;

/**
 * Class HandlesNotificationReads
 * @package App\Stateful\Behaviours
 *
 *
 * Request:
 * {
 *   message_id: 12345
 * }
 *
 */
class HandlesNotificationReads extends Behaviour
{

    public function on (): array
    {
        return [EventType::EVENT_FROM_FRONTEND_SEND_NOTIFICATION_READ];
    }

     /**
      * Models Messages  id
      **/
    public function triggered (\App\Stateful\EventProtocol $protocol)
    {
        $notification = Notification::findOrFail($protocol->getPayload()['id']);

        $user = $protocol->getSenderConnection()->getUser();

        if ($notification->user_id != $user->id)
            throw new \Exception('This user does not own this notification');

        tap($notification)->read();

        $this->broadcast(new EventProtocol($notification->user_id, EventType::EVENT_FROM_FRONTEND_SEND_NOTIFICATION_READ,  [
            'notification_id' => $notification->id
        ]));
    }
}
