<?php

namespace App\Http\Resources\Interaction;

use App\Http\Resources\Post\PostResource;
use Illuminate\Http\Resources\Json\JsonResource;

class PostInteractionResource extends JsonResource
{

    public function toArray($request)
    {
        return array_merge([
            'interaction_type'   => $this->type,
            'interaction_entity' => $this->interactionable_type
        ], (new PostResource($this->interactionable))->resolve());
    }
}
