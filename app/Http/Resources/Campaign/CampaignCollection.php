<?php

namespace App\Http\Resources\Campaign;

use App\Traits\Filtratable;
use Illuminate\Http\Resources\Json\ResourceCollection;

class CampaignCollection extends ResourceCollection
{
    use Filtratable;

    public $collects = CampaignResource::class;

    public function toArray($request)
    {
        return $this->processCollection($request);
    }
}
