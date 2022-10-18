<?php

namespace App\Http\Resources\Campaign;

use Illuminate\Http\Resources\Json\JsonResource;

class CampaignDetailResource extends JsonResource
{

    public function toArray($request)
    {
        return [
            'id'           => $this->id,
            'history'      => $this->clicks->pluck('created_at')->toArray(),
            'total_clicks' => $this->click_count,
            'leads'        => $this->register_user_count,
            'browsers'     => new ClickCollection($this->clicks),
        ];
    }
}
