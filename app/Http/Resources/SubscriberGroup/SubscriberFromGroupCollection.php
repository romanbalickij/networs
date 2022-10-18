<?php

namespace App\Http\Resources\SubscriberGroup;

use App\Traits\Filtratable;
use Illuminate\Http\Resources\Json\ResourceCollection;

class SubscriberFromGroupCollection extends ResourceCollection
{

    use Filtratable;

    public $collects = SubscriberResourceIndex::class;

    public function toArray($request)
    {
        return $this->processCollection($request);
    }
}
