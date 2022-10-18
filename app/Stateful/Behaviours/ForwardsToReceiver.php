<?php


namespace App\Stateful\Behaviours;


use App\Enums\EventType;
use App\Stateful\Behaviour;
use App\Stateful\ConnectionWrapper;
use App\Stateful\EventProtocol;

/**
 * Class ForwardsToReceiver
 * @package App\Stateful\Behaviours
 *
 *
 * Error payload:
 * {
 *   "error": "",
 *   "file": "",
 *   "line": "",
 *   "trace": []
 * }
 *
 *
 * Notification and message payload is forwarded from backend
 *
 */
class ForwardsToReceiver extends Behaviour
{

    function on (): array
    {
        return [
            'error',
            EventType::EVENT_NOTIFICATION,
            EventType::EVENT_MESSAGE,
            EventType::EVENT_REACTION_MESSAGE,
            EventType::EVENT_MARK_MESSAGE_READ, // @deprecated
        ];
    }

    function triggered (EventProtocol $protocol)
    {
        $this->broadcast($protocol);
    }
}
