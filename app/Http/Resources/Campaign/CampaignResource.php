<?php

namespace App\Http\Resources\Campaign;

use App\Traits\Filtratable;
use Illuminate\Http\Resources\Json\JsonResource;

class CampaignResource extends JsonResource
{

    use Filtratable;

    public function toArray($request)
    {
        return $this->filtrateFields([
            'id'     => $this->id,
            'name'   => $this->name,
            'clicks' => $this->click_count
        ]);
    }
}
