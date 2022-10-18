<?php

namespace App\Http\Resources\Admin\Content;

use App\Traits\Filtratable;
use Illuminate\Http\Resources\Json\ResourceCollection;

class InterfaceTextCollection extends ResourceCollection
{
    use Filtratable;

    public $collects = InterfaceTextResource::class;

    public function toArray($request)
    {
        return $this->processCollection($request);
    }
}
