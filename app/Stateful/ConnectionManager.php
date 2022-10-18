<?php


namespace App\Stateful;


use App\Models\User;
use Psr\Http\Message\RequestInterface;
use Ratchet\ConnectionInterface;
use Tymon\JWTAuth\Facades\JWTAuth;


class ConnectionManager
{

    public static int $socketId = 0;
    private array $connections = [];
    private array $uidIndex = [];



    public function addUser(ConnectionInterface $connection): ConnectionWrapper {
        $connection->app = new \stdClass();
        $connection->app->id = '0';
        $connection->app->name = '0';
        $connection->socketId = ++self::$socketId;

        $wrapper = new ConnectionWrapper($connection);

        /** @var User $user */
        if ($user = $this->authenticate($connection)) {
            $wrapper->setUser($user);

            $this->connections[$connection->socketId] = $wrapper;
            if (!isset($this->uidIndex[$user->id])) {
                $this->uidIndex[$user->id] = [];
            }
            $this->uidIndex[$user->id][] = $connection->socketId;

            return $wrapper;
        } else {
            throw new \Exception('Unauthenticated');
        }
    }


    private function authenticate(ConnectionInterface $connection): ?User {
        /** @var RequestInterface $request */
        $request = $connection->httpRequest;
        $params = [];
        parse_str($request->getUri()->getQuery(), $params);

        JWTAuth::setToken($params['token']);
        $user = JWTAuth::authenticate();
        if (!$user)
            return null;
        return $user;
    }


    public function cleanUpUser(ConnectionInterface $connection): ?ConnectionWrapper {
        $connectionWrapper = $this->connections[$connection->socketId];
        if (!$connectionWrapper)
            return null;
        unset($this->connections[$connection->socketId]);
        $uid = $connectionWrapper->uid();

        if (isset($this->uidIndex[$uid])) {
            $this->uidIndex[$uid] = array_filter($this->uidIndex[$uid], fn($x) => $x != $connectionWrapper->id());
        }
        if (isset($this->uidIndex[$uid]) && !$this->isOnline($uid)) {
            unset($this->uidIndex[$uid]);
        }
        return $connectionWrapper;
    }


    public function isOnline($uid) {
        return count($this->getByUID($uid) ?? []) > 0;
    }


    public function getByConnection(ConnectionInterface $connection): ?ConnectionWrapper {
        return $this->connections[$connection -> socketId] ?? null;
    }

    public function getByUID($id): ?array {
        return $this->uidIndex[$id] ?? null;
    }

    public function broadcast(EventProtocol $protocol) {
        if ($connections = $this->getByUID($protocol->getUidReceiver())) {
            foreach ($connections as $id) {
                /** @var ConnectionWrapper $conn */
                if (isset($this->connections[$id]))
                    $this->connections[$id]->digest($protocol);
                else RatchetAdapter::log("Trying to write to non-existing connection $id");
            }
        }
    }

}
