<?php


namespace App\Stateful;


use App\Enums\EventType;
use Ratchet\ConnectionInterface;
use Ratchet\RFC6455\Messaging\MessageInterface;
use Ratchet\WebSocket\MessageComponentInterface;
use React\EventLoop\Loop;
use React\EventLoop\LoopInterface;

/**
 * Class RatchetAdapter
 * @package Stateful
 *
 *
 * Protocol requires to use:
 * - User ID (uid)
 * - Event Name (event)
 * - Payload (custom array for event)
 *
 * For backend-to-server requests, additionally, a timestamp
 * field with current integer timestamp is required
 * for signature validation
 *
 *
 *
 * Events list:
 *
 * Internal:
 * - open
 * - close
 * - error
 *
 * Server-side:
 * - notification
 * - message
 *
 * Client-side:
 * - typing.{id}
 *
 * Diagnostics
 *
 * TODO behaviours
 *  - ManagesStatsSubscriptions - subscribe to stats updates (additional auth required)
 *
 *
 * TODO later
 *  - messageRead [userSends]
 *  - messageReact [userSends]
 *  - notificationRead [userSends]
 *  - postInterest [userSends]
 *  - postClickthrough [userSends]
 */

class RatchetAdapter implements MessageComponentInterface
{
    private LoopInterface $loop;
    private ConnectionManager $manager;
    private EventBus $bus;

    const ALLOWED_CLIENT_EVENTS = [
        'typing',
        EventType::EVENT_FROM_FRONTEND_SEND_MESSAGE_READ,
        EventType::EVENT_FROM_FRONTEND_SEND_NOTIFICATION_READ,
        EventType::EVENT_FROM_FRONTEND_SEND_TEXT_MESSAGE,

        'messageReact', // TODO
        EventType::EVENT_FROM_FRONTEND_POST_INTEREST,
        EventType::EVENT_FROM_FRONTEND_POST_CLICK,
    ];



    public function __construct ()
    {
        $this->loop = Loop::get();
        $this->manager = new ConnectionManager();

        $this->bus = EventBus::get();
        $this->bus->setManager($this->manager);

        $tick = 1;
        $this->loop->addPeriodicTimer(1, function() use (&$tick){
            $this->onTick($tick++);
        });
    }

    public static function log($message) {
        $date = date('d.m.Y H:i:s');
        echo "[WS][$date] $message\n"; // TODO better logging
    }


    public function onOpen (ConnectionInterface $conn)
    {
        try {
            $wrappedConnection = $this->manager->addUser($conn);
            $this->bus->emit(new EventProtocol($wrappedConnection, 'open'));
        } catch (\Exception $e) {
            $conn->send(json_encode([
                'event' => 'error',
                'payload' => [
                    'error' => 'Unauthenticated'
                ]
            ]));
        }
    }


    public function onClose (ConnectionInterface $conn)
    {
        $wrappedConnection = $this->manager->cleanUpUser($conn);
        if ($wrappedConnection) {
            $this->bus->emit(new EventProtocol($wrappedConnection, 'close'));
        }
    }


    public function onError (ConnectionInterface $conn, \Exception $e)
    {
        $wrappedConnection = $this->manager->getByConnection($conn);
        $error = [
            'error' => $e->getMessage()
        ];

        self::log("Error: {$e->getMessage()}");
        if (env('APP_DEBUG'))
            self::log("File: {$e->getFile()}, line: {$e->getLine()}");

        if (!$wrappedConnection)
            return;

        if (env('APP_DEBUG')) {
            $error['file'] = $e->getFile();
            $error['line'] = $e->getLine();
            $error['trace'] = $e->getTraceAsString();
        }
        $this->bus->emit(new EventProtocol($wrappedConnection, 'error', $error));
    }


    public function onMessage (ConnectionInterface $conn, MessageInterface $msg)
    {
        $wrappedConnection = $this->manager->getByConnection($conn);
        if (!$wrappedConnection)
            throw new \Exception("Received message from ghost");

        $message = json_decode($msg->__toString(), true);
        list ('uid' => $uid, 'event' => $event, 'payload' => $payload) = $message;

        if (!$uid || !$event || !is_array($payload))
            throw new \Exception('Fields missing. Required fields are uid, event and payload');

        if (!in_array(explode('.', $event)[0], self::ALLOWED_CLIENT_EVENTS))
            throw new \Exception('This event cannot be processed from client-side');

        $this->bus->emit(new EventProtocol($wrappedConnection, $event, $payload, $uid));
    }


    public function onTick(int $tick) {
        // TODO Get RAM usage
        $usage = number_format(memory_get_usage());
        if (!($tick % 120))
            self::log("RAM usage: $usage bytes");

        // TODO get user count and unique user count

        // TODO measure other metrics

    }
}
