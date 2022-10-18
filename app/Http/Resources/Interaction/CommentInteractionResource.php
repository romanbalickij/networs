<?php

namespace App\Http\Resources\Interaction;

use App\Http\Resources\Comment\CommentResource;
use App\Traits\Filtratable;
use Illuminate\Http\Resources\Json\JsonResource;

class CommentInteractionResource extends JsonResource
{
    use Filtratable;

    public function toArray($request)
    {
        return $this->filtrateFields(array_merge([
            'interaction_type'   => $this->type,
            'interaction_entity' => $this->interactionable_type
        ], (new CommentResource($this->interactionable))->resolve())

        );
    }
}
