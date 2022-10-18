<?php


namespace App\Services\Actions;


use App\Models\AdCampaign;

class CampaignClickAction
{

    public function handler(AdCampaign $campaign, array $userPayload) :void {

        $campaign->addClick($userPayload);
    }
}
