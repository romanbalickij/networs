<?php

namespace App\Models\Traits\User;

use App\Models\Plan;
use App\Models\Subscription;
use App\Models\User;

trait HasSubscriptions
{

    //My subscribers - це люди які підписані на мене і які мені платять
    public function subscribers() {

        return $this->hasMany(Subscription::class, 'creator_id');
    }

    //My subscriptions - це люди, на яких я підписаний, і яким я плачу
    public function subscriptions() {

        return $this->hasMany(Subscription::class);
    }

    public function isMySubscribed($user)  {

        return (boolean) $this->getMySubscribe($user);

//        return $this
//            ->subscribers()
//            ->where(fn ($query) => $query->where('user_id', $user->id)->paid()->withinLastMonth())
//            ->exists();
    }

    public function isMySubscription($user) :bool {

        return $this->subscriptions()
            ->creator($user)
            ->paid()
            ->withinLastMonth()
            ->exists();
    }

    public function existSubscription($Subscription) {

        return $this->subscriptions()
            ->creator($Subscription)
            ->exists();
    }

    public function hasSubscriptions() {

        return $this->subscriptions()->exists();
    }

    public function hasSubscribed(User $subscription):bool {

        return $this->subscriptions()
            ->creator($subscription)
            ->count();
    }

    public function subscribe(User $subscriber, Plan $plan) {

      //  dd($this->hasSubscribed($subscriber), $subscriber);
        $subscription = $this->hasSubscribed($subscriber)
            ? $this->subscribedUpdate($subscriber, $plan)
            : $this->subscribedCreate($subscriber, $plan);

        return $subscription->paid();
    }

    public function getMysubscription($user) {

        return $this
            ->subscriptions()
            ->creator($user)
            ->first();
    }

    public function unsubscribe(Subscription $subscription):void {

        $subscription->unsubscribe();
    }

    public function getSubscriberGroupId($user) {

        return $this
            ->subscribers()
            ->where(fn ($query) => $query->where('user_id', $user->id)->paid()->withinLastMonth())
            ->pluck('subscriber_group_id');
    }

    public function getMySubscribe($user) {

        return $this
            ->subscribers()
            ->where(fn ($query) => $query->where('user_id', $user->id)->paid()->withinLastMonth())
            ->first();
    }

    protected function subscribedUpdate(User $subscriber, Plan $plan) {

        return tap($this->subscriptions()
            ->creator($subscriber)->first())
            ->update(['plan_id' => $plan->id]);
    }

    protected function subscribedCreate(User $subscriber, Plan $plan) {

        return $this
            ->subscriptions()
            ->create(['creator_id' => $subscriber->id, 'plan_id' => $plan->id]);
    }

    public function allowedPrivatePostFor($user) {

      if(!$user){return false;}

      return $this->subscribers
          ->contains(
              fn ($subscriber) => ($subscriber->user_id === $user->id and $subscriber->is_paid == true)
          );
    }

}
