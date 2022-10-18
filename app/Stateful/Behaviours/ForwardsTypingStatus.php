<?php


namespace App\Stateful\Behaviours;


use App\Models\Chat;
use App\Models\User;
use App\Stateful\Behaviour;
use App\Stateful\EventProtocol;

class ForwardsTypingStatus extends Behaviour
{

    public function on (): array
    {
        return ['typing'];
    }

    public function triggered (EventProtocol $protocol)
    {
        if ($protocol->getUidSender() === $protocol->getUidReceiver())
            throw new \Exception('Cannot forward typing to yourself');

        $eventRoute = explode('.', $protocol->getEvent());
        $chat = Chat::findOrFail($eventRoute[1] ?? null);

        $ids = [$chat->service_id, $chat->client_id];
        if (!in_array($protocol->getUidSender(), $ids) || !in_array($protocol->getUidReceiver(), $ids))
            throw new \Exception('These users do not belong to the chat or chat invalid');

        (new ForwardsToReceiver($this->connectionManager)) -> triggered($protocol);
    }
}
