<?php

namespace App\Http\Resources\User;

use App\Traits\Filtratable;
use Illuminate\Http\Resources\Json\ResourceCollection;

class UserInvoiceCollection extends ResourceCollection
{
    use Filtratable;

    public $collects = UserInvoiceResource::class;

    public function toArray($request)
    {
        return $this->processCollection($request);
    }
}
