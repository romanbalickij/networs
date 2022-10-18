<?php

namespace App\Http\Resources\SubscriberGroup;

use App\Traits\Filtratable;
use Illuminate\Http\Resources\Json\ResourceCollection;

class SubscriberGroupIndexCollection extends ResourceCollection
{

    use Filtratable;

    public $collects = SubscriberGroupIndexResource::class;

    public function toArray($request)
    {
        return $this->processCollection($request);
    }
}
