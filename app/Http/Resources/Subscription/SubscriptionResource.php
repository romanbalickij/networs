<?php

namespace App\Http\Resources\Subscription;

use App\Http\Resources\Post\PostResource;
use App\Http\Resources\SubscriberGroup\SubscriberGroupResource;
use App\Http\Resources\User\UserResource;
use App\Traits\Filtratable;
use Illuminate\Http\Resources\Json\JsonResource;

class SubscriptionResource extends JsonResource
{
    use Filtratable;

    public function toArray($request)
    {
        return $this->filtrateFields([
            'id'           => $this->id,
            'monthly_cost' => $this->plan->monthly_cost,
            'next_payment' => $this->nextPayment(),

            'user'         => (new UserResource($this->owner))->only('id', 'name', 'surname', 'background', 'avatar', 'is_bookmarked', 'nickname'),
            'last_post'    => (new PostResource($this->owner->post))->except('interested', 'clickthroughs', 'shows', 'is_pinned'),
            'group'        => new SubscriberGroupResource($this->subscriptionGroup)
        ]);
    }
}
