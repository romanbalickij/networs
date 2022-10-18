<?php

namespace App\Http\Resources\Landing;

use App\Traits\Filtratable;
use Illuminate\Http\Resources\Json\ResourceCollection;

class PageCollection extends ResourceCollection
{
    use Filtratable;

    public $collects = PageResource::class;

    public function toArray($request)
    {
        return $this->processCollection($request);
    }
}
