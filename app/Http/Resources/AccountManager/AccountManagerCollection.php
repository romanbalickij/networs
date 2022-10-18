<?php

namespace App\Http\Resources\AccountManager;

use App\Traits\Filtratable;
use Illuminate\Http\Resources\Json\ResourceCollection;

class AccountManagerCollection extends ResourceCollection
{
    use Filtratable;

    public $collects = AccountManagerResource::class;

    public function toArray($request)
    {
        return $this->processCollection($request);
    }
}
