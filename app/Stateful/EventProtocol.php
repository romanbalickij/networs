<?php


namespace App\Stateful;


class EventProtocol
{
    private int $uidReceiver;
    private int $uidSender;
    private string $event;
    private array $payload;
    private ?ConnectionWrapper $senderConnection;

    public function __construct (ConnectionWrapper|int|null $user, string $event, array $payload = [],int $uidReceiver = null)
    {
        if (!$user)
            throw new \Exception('Tried to send an event to null');
        if ($user instanceof ConnectionWrapper) {
            $this->senderConnection = $user;
            $this->uidSender = $user->uid();
        } else {
            $this->uidSender = $user;
        }
        $this->event = $event;
        $this->payload = $payload;
        $this->uidReceiver = $uidReceiver ?? $this->uidSender;

        RatchetAdapter::log("Event constructed from user #$this->uidSender to #$this->uidReceiver: $this->event");
    }

    public function toJson() {
        return json_encode([
            'uid' => $this->uidReceiver, // To check on frontend, just in case
            'event' => $this->event,
            'payload' => $this->payload
        ]);
    }

    public static function fromJson(string $json) {
        return static::fromArray(json_decode($json, TRUE));
    }

    public static function fromArray(array $data) {
        list ('uid' => $uid, 'event' => $event, 'payload' => $payload) = $data;

        return new self($uid, $event, $payload);

    }

    /**
     * @return int
     */
    public function getUidReceiver (): int
    {
        return $this->uidReceiver;
    }

    /**
     * @return int|string
     */
    public function getEvent (): int|string
    {
        return $this->event;
    }

    /**
     * @return array
     */
    public function getPayload (): array
    {
        return $this->payload;
    }

    /**
     * @return int
     */
    public function getUidSender (): int
    {
        return $this->uidSender;
    }

    /**
     * @return ConnectionWrapper|null
     */
    public function getSenderConnection (): ?ConnectionWrapper
    {
        return $this->senderConnection;
    }
}
