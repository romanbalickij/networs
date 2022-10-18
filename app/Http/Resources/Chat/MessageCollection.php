<?php

namespace App\Http\Resources\Chat;

use App\Traits\Filtratable;
use Illuminate\Http\Resources\Json\ResourceCollection;

class MessageCollection extends ResourceCollection
{
    use Filtratable;

    public $collects = MessageResource::class;

    public function toArray($request)
    {
        return $this->processCollection($request);
    }
}
