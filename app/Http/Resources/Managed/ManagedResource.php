<?php

namespace App\Http\Resources\Managed;

use App\Traits\Filtratable;
use Illuminate\Http\Resources\Json\JsonResource;

class ManagedResource extends JsonResource
{
    use Filtratable;

    public function toArray($request)
    {
        return $this->filtrateFields([
            'id'  => $this->id,
            'man' => $this->manages_user_id
        ]);
    }
}
