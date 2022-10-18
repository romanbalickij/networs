<?php

namespace App\Http\Resources\SubscriberGroup;

use App\Traits\Filtratable;
use Illuminate\Http\Resources\Json\JsonResource;

class SubscriberGroupResource extends JsonResource
{
    use Filtratable;

    public function toArray($request)
    {
        return $this->filtrateFields([
            'id'   => $this->id,
            'name' => $this->name,
        ]);
    }
}
