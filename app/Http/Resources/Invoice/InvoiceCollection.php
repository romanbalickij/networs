<?php

namespace App\Http\Resources\Invoice;

use App\Traits\Filtratable;
use Illuminate\Http\Resources\Json\ResourceCollection;

class InvoiceCollection extends ResourceCollection
{
    use Filtratable;

    public $collects = InvoiceResource::class;

    public function toArray($request)
    {
        return $this->processCollection($request);
    }
}
