<?php

namespace App\Http\Resources\User;

use App\Traits\Filtratable;
use Illuminate\Http\Resources\Json\ResourceCollection;

class PlanCollection extends ResourceCollection
{
   use Filtratable;

    public $collects = PlanResource::class;

    public function toArray($request)
    {
        return $this->processCollection($request);
    }
}
