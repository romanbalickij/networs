<?php


namespace App\Stateful;


use App\Models\User;
use App\Stateful\Interfaces\Protocol;
use Ratchet\ConnectionInterface;

class ConnectionWrapper
{
    private ConnectionInterface $connection;
    private User $user;

    public function __construct (ConnectionInterface $connection)
    {
        $this->connection = $connection;
    }

    public function id()
    {
        return $this->connection->socketId;
    }

    public function uid()
    {
        return $this->user->id;
    }


    public function getUser (): User
    {
        return $this->user;
    }


    public function setUser (User $user): void
    {
        $this->user = $user;
    }


    public function digest(EventProtocol $protocol) {
        $this->connection->send($protocol->toJson());
    }


}
