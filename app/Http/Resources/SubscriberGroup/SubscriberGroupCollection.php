<?php

namespace App\Http\Resources\SubscriberGroup;

use App\Traits\Filtratable;
use Illuminate\Http\Resources\Json\ResourceCollection;

class SubscriberGroupCollection extends ResourceCollection
{
    use Filtratable;

    public $collects = SubscriberGroupResource::class;

    public function toArray($request)
    {
        return $this->processCollection($request);
    }
}
