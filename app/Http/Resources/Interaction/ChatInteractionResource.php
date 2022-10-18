<?php

namespace App\Http\Resources\Interaction;

use App\Http\Resources\Chat\ConversationResource;
use Illuminate\Http\Resources\Json\JsonResource;

class ChatInteractionResource extends JsonResource
{

    public function toArray($request)
    {
        return array_merge([
            'interaction_type'   => $this->type,
            'interaction_entity' => $this->interactionable_type
        ], (new ConversationResource($this->interactionable))->resolve());
    }
}
