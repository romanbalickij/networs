<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Campaign\ClickCampaignRequest;
use App\Models\AdCampaign;
use App\Services\Actions\CampaignClickAction;
use Illuminate\Http\Request;

class ClickCampaignController extends BaseController
{
    public function __invoke(AdCampaign $campaign, ClickCampaignRequest $request, CampaignClickAction $clickAction) {

        $clickAction->handler($campaign, $request->getPayload());

        return $this->respondWithSuccess('Ok');
    }
}
