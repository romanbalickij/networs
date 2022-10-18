<?php

namespace App\Http\Resources\Campaign;

use App\Traits\Filtratable;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ClickCollection extends ResourceCollection
{

    use Filtratable;

    public $collects = ClickResource::class;

    public function toArray($request)
    {
        return $this->processCollection($request);
    }
}
