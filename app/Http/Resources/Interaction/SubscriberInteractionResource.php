<?php

namespace App\Http\Resources\Interaction;

use App\Http\Resources\Subscriber\SubscriberResource;
use Illuminate\Http\Resources\Json\JsonResource;

class SubscriberInteractionResource extends JsonResource
{

    public function toArray($request)
    {
        return array_merge([
            'interaction_type'   => $this->type,
            'interaction_entity' => $this->interactionable_type
        ], (new SubscriberResource($this->interactionable))->resolve());
    }
}
