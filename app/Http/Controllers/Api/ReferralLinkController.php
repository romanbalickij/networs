<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\BaseController;
use App\Http\Requests\ReferralLink\ReferralLinkRequest;
use App\Http\Resources\ReferralLink\ReferralInvitedUserCollection;
use App\Http\Resources\ReferralLink\ReferralLinkCollection;
use App\Http\Resources\ReferralLink\ReferralLinkResource;
use App\Models\ReferralLink;
use App\Services\Actions\AddReferralAction;


class ReferralLinkController extends BaseController
{
    public function index() {

        $user = user()->load([
            'referralLinks'       => fn($query) => $query->countUsers(),
            'referralLinks.users' => fn($q) => $q->referralTotalEarned()]);

        return $this->respondWithSuccess(

            new ReferralLinkCollection($user->referralLinks)
        );
    }

    public function store(ReferralLinkRequest $referralLinkRequest, AddReferralAction $referralAction) {

        return $this->respondWithSuccess(

               new ReferralLinkResource($referralAction->execute($referralLinkRequest->payload()))
        );

    }

    public function show(ReferralLink $referralLink) {

        $referralLink->load((['users' => fn($q) => $q->referralTotalEarned()]));

        return $this->respondWithSuccess(

            new ReferralInvitedUserCollection($referralLink->users)
        );
    }

    public function destroy(ReferralLink $referralLink) {

        $referralLink->delete();

        return $this->respondOk('The ReferralLink deleted successfully');
    }
}
