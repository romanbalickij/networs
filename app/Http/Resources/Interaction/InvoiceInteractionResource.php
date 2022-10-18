<?php

namespace App\Http\Resources\Interaction;

use App\Http\Resources\Invoice\InvoiceResource;
use Illuminate\Http\Resources\Json\JsonResource;

class InvoiceInteractionResource extends JsonResource
{

    public function toArray($request)
    {
        return array_merge([
            'interaction_type'   => $this->type,
            'interaction_entity' => $this->interactionable_type
        ], (new InvoiceResource($this->interactionable))->resolve());
    }
}
