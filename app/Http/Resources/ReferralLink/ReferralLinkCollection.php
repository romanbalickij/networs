<?php

namespace App\Http\Resources\ReferralLink;

use App\Traits\Filtratable;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ReferralLinkCollection extends ResourceCollection
{
    use Filtratable;

    public $collects = ReferralLinkResource::class;

    public function toArray($request)
    {
        return $this->processCollection($request);
    }
}
