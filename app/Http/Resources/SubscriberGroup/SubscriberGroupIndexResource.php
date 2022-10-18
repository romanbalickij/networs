<?php

namespace App\Http\Resources\SubscriberGroup;

use App\Traits\Filtratable;
use Illuminate\Http\Resources\Json\JsonResource;

class SubscriberGroupIndexResource extends JsonResource
{
    use Filtratable;


    public function toArray($request)
    {
        return $this->filtrateFields([
            'id'               => $this->id,
            'name'             => $this->name,
            'subscriber_count' => $this->subscriptions_count,
            'last_subscribers' => (new SubscriberFromGroupCollection($this->subscriptionsTake(3)))
        ]);
    }
}
