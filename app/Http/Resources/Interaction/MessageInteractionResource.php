<?php

namespace App\Http\Resources\Interaction;

use App\Http\Resources\Chat\MessageResource;
use Illuminate\Http\Resources\Json\JsonResource;

class MessageInteractionResource extends JsonResource
{

    public function toArray($request)
    {
        return array_merge([
            'interaction_type'   => $this->type,
            'interaction_entity' => $this->interactionable_type
        ], (new MessageResource($this->interactionable))->resolve());
    }
}
