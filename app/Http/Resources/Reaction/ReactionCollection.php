<?php

namespace App\Http\Resources\Reaction;

use App\Traits\Filtratable;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ReactionCollection extends ResourceCollection
{
    use Filtratable;

    public $collects = ReactionResource::class;

    public function toArray($request)
    {
        return $this->processCollection($request);
    }
}
