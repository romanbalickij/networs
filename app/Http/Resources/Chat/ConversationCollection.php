<?php

namespace App\Http\Resources\Chat;

use App\Traits\Filtratable;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ConversationCollection extends ResourceCollection
{
    use Filtratable;

    public $collects = ConversationResource::class;

    public function toArray($request)
    {
        return $this->processCollection($request);
    }
}
