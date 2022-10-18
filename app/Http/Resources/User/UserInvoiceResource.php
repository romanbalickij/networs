<?php

namespace App\Http\Resources\User;

use App\Traits\Filtratable;
use Illuminate\Http\Resources\Json\JsonResource;

class UserInvoiceResource extends JsonResource
{
     use Filtratable;

    public function toArray($request)
    {
        return $this->filtrateFields([
            'id'             => $this->id,
            'type'           => $this->type,
            'purpose_string' => $this->purpose_string,
            'sum'            => $this->sum
        ]);
    }
}
