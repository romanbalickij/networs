<?php


namespace App\Stateful;


use App\Stateful\ConnectionManager;
use App\Stateful\EventProtocol;

abstract class Behaviour
{
    protected $connectionManager;

    public function __construct (ConnectionManager $manager)
    {
        $this->connectionManager = $manager;
    }

    protected function broadcast(EventProtocol $protocol) {
        $this->connectionManager->broadcast($protocol);
    }

    abstract public function on(): array;

    abstract public function triggered(EventProtocol $protocol);
}
