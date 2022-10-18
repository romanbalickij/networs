<?php


namespace App\Services\Actions;

use App\Enums\InteractionType;
use App\Models\User;

class CancelMySubscribedAction
{

    public function execute(User $user) {

        \user()->existSubscription($user) ?: throw new \Exception('this is not my subscriber ()_()');

        $subscription = user()->getMysubscription($user);

        //interactions  Subscription cancellations
        $subscription->pushToInteractions(InteractionType::TYPE_SUBSCRIPTION_CANCELLATION, user());

        //interactions  Subscriber cancellations
        $subscription->pushToInteractions(InteractionType::TYPE_SUBSCRIBER_CANCELLATION, $subscription->owner);

         user()->unsubscribe($subscription);
    }
}
