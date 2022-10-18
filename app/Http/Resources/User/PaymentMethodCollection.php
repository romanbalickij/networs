<?php

namespace App\Http\Resources\User;

use App\Traits\Filtratable;
use Illuminate\Http\Resources\Json\ResourceCollection;

class PaymentMethodCollection extends ResourceCollection
{
    use Filtratable;

    public $collects = PaymentMethodResource::class;

    public function toArray($request)
    {
        return $this->processCollection($request);
    }
}
