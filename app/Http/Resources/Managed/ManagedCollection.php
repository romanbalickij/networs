<?php

namespace App\Http\Resources\Managed;

use App\Traits\Filtratable;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ManagedCollection extends ResourceCollection
{

    public $collects = ManagedResource::class;

    use Filtratable;

    public function toArray($request)
    {
        return $this->processCollection($request);
    }
}
