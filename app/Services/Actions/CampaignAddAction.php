<?php


namespace App\Services\Actions;


use App\Models\AdCampaign;
use App\Services\DataTransferObjects\CampaignDto;

class CampaignAddAction
{

    public function execute(CampaignDto $campaignDto) {

        return AdCampaign::create(collect($campaignDto)->merge([
            'user_id' => user()->id

        ])->toArray());
    }
}
