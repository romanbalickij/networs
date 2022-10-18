<?php


namespace App\Services\Actions;


use App\Enums\InteractionType;
use App\Models\Plan;
use App\Models\User;

class SubscribeAction
{


    public function handler(User $user, Plan $plan) {

        $subscribe = user()->subscribe($user, $plan);

        // interactions Subscriptions
        $subscribe->pushToInteractions(InteractionType::TYPE_SUBSCRIPTION, user());

        // interactions New subscribers
        $subscribe->pushToInteractions(InteractionType::TYPE_NEW_SUBSCRIBER, $subscribe->owner);

        //create chat to user  end api add/ delete from here
      //  (new ChatService(new ConversationUser()))->createRoom(user(), $user->id);

        return $subscribe;
    }
}
