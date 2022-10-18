<?php

namespace App\Http\Resources\User;

use App\Traits\Filtratable;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ManagerAccountCollection extends ResourceCollection
{
    use Filtratable;

    public $collects = ManagerAccountResource::class;

    public function toArray($request)
    {
        return $this->processCollection($request);
    }
}
