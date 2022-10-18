<?php

namespace App\Http\Resources\User;

use App\Traits\Filtratable;
use Illuminate\Http\Resources\Json\JsonResource;

class PaymentMethodResource extends JsonResource
{
    use Filtratable;

    public function toArray($request)
    {
        return $this->filtrateFields([
            'id'          => $this->id,
            'card_type'   => $this->card_type,
            'last_four'   => $this->last_four,
            'default'     => $this->default,
            'name'        => $this->name,
        ]);
    }
}
