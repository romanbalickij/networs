<?php

namespace App\Models\Traits\Subscription;

use App\Models\SubscriberGroup;

trait HasSubscriptionGroup
{

    public function subscriptionGroup() {

        return $this->belongsTo(SubscriberGroup::class, 'subscriber_group_id');
    }

    public function setGroup(SubscriberGroup $subscriberGroup) {

       return $this->update(['subscriber_group_id' => $subscriberGroup->id]);
    }

    public function deleteGroup() {

        return $this->update(['subscriber_group_id' => NULL]);
    }
}
