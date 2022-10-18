<?php


namespace App\Stateful\Behaviours;


use App\Models\User;
use App\Stateful\Behaviour;
use App\Stateful\EventProtocol;

class MonitorsOnlineStatus extends Behaviour
{
    function on (): array
    {
        return ['open', 'close'];
    }

    function triggered (EventProtocol $protocol)
    {
        $connection = $protocol->getSenderConnection();
        $user = $connection->getUser();

        $user->is_online = $this->connectionManager->isOnline($user->id);
        $user->save();
    }
}
