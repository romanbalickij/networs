<?php

namespace App\Models\Traits\User;

use App\Models\SubscriberGroup;

trait HasSubscriberGroups
{


    public function subscriberGroups() {

        return $this->hasMany(SubscriberGroup::class, 'creator_id');
    }

    public function createDefaultGroup() {

        $this->subscriberGroups()->create([
           'name' =>  $this->defaultGroup()
        ]);
    }

    private function defaultGroup() {

        return 'Close Friends';
    }
}
