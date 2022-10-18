<?php

namespace App\Models\Traits\User;

use App\Models\AdCampaign;

trait HasAdCampaigns
{

    public function adCampaigns() {

        return $this->hasMany(AdCampaign::class);
    }
}
