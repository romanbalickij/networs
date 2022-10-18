<?php

namespace App\Http\Resources\Subscriber;

use App\Enums\SettingType;
use App\Http\Resources\SubscriberGroup\SubscriberGroupResource;
use App\Http\Resources\User\UserResource;
use App\Traits\Filtratable;
use Illuminate\Http\Resources\Json\JsonResource;

class SubscriberResource extends JsonResource
{
    use Filtratable;

    public function toArray($request)
    {
        return $this->filtrateFields([
            'id'                => $this->id,
            'chat_id'           => optional($this->user->chat)->id,
            'plan_name'         => $this->plan->name,
            'plan_monthly_cost' => $this->plan->monthly_cost,
            'autoprolong'       => $this->user->setting(SettingType::AUTO_PROLONG_SUBSCRIPTION),

            'user'              => (new UserResource($this->user))->only('id', 'name', 'surname','avatar', 'background', 'nickname', 'description', 'is_online'),
            'group'             => new SubscriberGroupResource($this->subscriptionGroup)

        ]);
    }
}
