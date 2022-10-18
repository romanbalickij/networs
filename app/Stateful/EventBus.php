<?php


namespace App\Stateful;


use App\Stateful\Behaviours\ForwardsToReceiver;
use App\Stateful\Behaviours\ForwardsTypingStatus;
use App\Stateful\Behaviours\HandlesMessageReads;
use App\Stateful\Behaviours\HandlesNotificationReads;
use App\Stateful\Behaviours\MonitorsOnlineStatus;
use App\Stateful\Behaviours\ReceivesTextMessages;
use App\Stateful\Behaviours\Welcomes;

class EventBus
{
    private array $behaviours;
    private static $instance;
    private ?ConnectionManager $manager = null;

    public static function get() {
        if (self::$instance) {
            return self::$instance;
        }
        else return self::$instance = new self();
    }

    public function setManager (ConnectionManager $manager): void
    {
        $this->manager = $manager;
        $this->behaviours = [
            new Welcomes($manager),
            new ForwardsToReceiver($manager),
            new MonitorsOnlineStatus($manager),

            new ReceivesTextMessages($manager),
            new ForwardsTypingStatus($manager),

            new HandlesMessageReads($manager),
            new HandlesNotificationReads($manager),
        ];
    }

    public function emit(EventProtocol $protocol) {
        if (!$this->manager)
            throw new \Exception('Event bus uninitialized');
        foreach ($this->behaviours as $behaviour) {
            /**
             * @var Behaviour $behaviour
             */
            foreach ($behaviour->on() as $eventType) {
                if (str_contains($protocol -> getEvent(), $eventType)) {
                    $behaviour -> triggered($protocol);
                }
            }
        }
    }
}
