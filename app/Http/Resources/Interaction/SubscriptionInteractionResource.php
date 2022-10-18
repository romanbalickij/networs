<?php

namespace App\Http\Resources\Interaction;

use App\Http\Resources\Subscription\SubscriptionResource;
use Illuminate\Http\Resources\Json\JsonResource;

class SubscriptionInteractionResource extends JsonResource
{

    public function toArray($request)
    {
        return array_merge([
            'interaction_type'   => $this->type,
            'interaction_entity' => $this->interactionable_type
        ], (new SubscriptionResource($this->interactionable))->resolve());
    }
}
