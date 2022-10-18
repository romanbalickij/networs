<?php

namespace App\Http\Resources\Campaign;

use App\Traits\Filtratable;
use Illuminate\Http\Resources\Json\JsonResource;

class ClickResource extends JsonResource
{
    use Filtratable;

    public function toArray($request)
    {
        return [
            'id'         => $this->id,
            'user_agent' => $this->user_agent,
            'user_ip'    => $this->user_ip
        ];
    }
}
