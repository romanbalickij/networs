<?php

namespace App\Http\Resources\Interaction;

use App\Http\Resources\Donation\DonationResource;
use Illuminate\Http\Resources\Json\JsonResource;

class DonationInteractionResource extends JsonResource
{

    public function toArray($request)
    {
        return array_merge([
            'interaction_type'   => $this->type,
            'interaction_entity' => $this->interactionable_type
        ], (new DonationResource($this->interactionable))->resolve());
    }
}
