<?php


namespace App\Stateful\Behaviours;


use App\Stateful\Behaviour;
use App\Stateful\EventProtocol;

/**
 * Class Welcomes
 * @package App\Stateful\Behaviours
 *
 *  * Response payload:
 * {
 *   "countSessions": 7,
 *   "socketId": 1248,
 * }
 */
class Welcomes extends Behaviour
{

    public function on (): array
    {
        return ['open'];
    }

    public function triggered (EventProtocol $protocol)
    {
        // TODO transfer some metadata, like other connections
        $this->broadcast(new EventProtocol($protocol->getUidReceiver(), 'device_joined', [
            'countSessions' => count($this->connectionManager->getByUID($protocol->getUidReceiver() ?? [])),
            'socketId' => $protocol->getSenderConnection()->id()
        ]));
    }
}
