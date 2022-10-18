<?php

namespace App\Http\Resources\Interaction;

use App\Http\Resources\User\UserResource;
use Illuminate\Http\Resources\Json\JsonResource;

class UserInteractionResource extends JsonResource
{
    public function toArray($request)
    {
        return array_merge([
            'interaction_type'   => $this->type,
            'interaction_entity' => $this->interactionable_type
        ], (new UserResource($this->interactionable))->resolve());
    }
}
