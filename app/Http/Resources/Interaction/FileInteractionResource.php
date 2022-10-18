<?php

namespace App\Http\Resources\Interaction;

use App\Http\Resources\Attachment\AttachmentResource;
use Illuminate\Http\Resources\Json\JsonResource;

class FileInteractionResource extends JsonResource
{

    public function toArray($request)
    {
        return array_merge([
            'interaction_type'   => $this->type,
            'interaction_entity' => $this->interactionable_type
        ], (new AttachmentResource($this->interactionable))->resolve());
    }
}
