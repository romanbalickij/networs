<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Campaign\CampaignRequest;
use App\Http\Resources\Campaign\CampaignCollection;
use App\Http\Resources\Campaign\CampaignDetailResource;
use App\Http\Resources\Campaign\CampaignResource;
use App\Models\AdCampaign;
use App\Services\Actions\CampaignAddAction;
use App\Services\Actions\CampaignUpdateAction;

class AdCampaignController extends BaseController
{

    public function index() {

        return $this->respondWithSuccess(

            new CampaignCollection(user()->adCampaigns)
        );
    }

    public function show($id) {

        $adCampaign = AdCampaign::withCount(['clicks as register_user_count' => fn($q) => $q->registerUser()])->findOrFail($id);

        return $this->respondWithSuccess(

            new CampaignDetailResource($adCampaign)
        );
    }

    public function store(CampaignRequest $request, CampaignAddAction $campaignAddAction) {

        $campaign = $campaignAddAction->execute($request->getDto());

        return $this->respondWithSuccess(

            new CampaignResource($campaign)
        );
    }

    public function update(CampaignRequest $request, AdCampaign $campaign, CampaignUpdateAction $campaignUpdateAction) {


        $this->authorize('update', $campaign);

        $response = $campaignUpdateAction->execute($campaign, $request->getDto());

        return $this->respondWithSuccess(

            new CampaignResource($response)
        );
    }

    public function destroy(AdCampaign $campaign) {

        $this->authorize('delete', $campaign);

        $campaign->delete();

        return $this->respondOk('The AdCampaign deleted successfully');
    }

}
