<?php

namespace App\Models\Traits\Click;

use App\Models\AdCampaign;

trait HasCampaign
{

    public function adCampaign() {

        return $this->belongsTo(AdCampaign::class);
    }
}
