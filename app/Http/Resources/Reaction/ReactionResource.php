<?php

namespace App\Http\Resources\Reaction;

use App\Traits\Filtratable;
use Illuminate\Http\Resources\Json\JsonResource;

class ReactionResource extends JsonResource
{
     use Filtratable;

    public function toArray($request)
    {
        return $this->filtrateFields([
            'id'       => $this->id,
            'reaction' => $this->reaction
        ]);
    }
}
