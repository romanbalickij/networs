<?php


namespace App\Services\Actions;


use App\Models\AdCampaign;
use App\Services\DataTransferObjects\CampaignDto;

class CampaignUpdateAction
{

    public function execute(AdCampaign $campaign, CampaignDto $campaignDto) {

        return tap($campaign)->update($campaignDto->toArray());
    }
}
