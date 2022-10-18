<?php

namespace App\Policies;

use App\Models\AdCampaign;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CampaignPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function delete (User $user, AdCampaign $campaign) {

        return $campaign->isAuthoredBy($user);
    }

    public function update (User $user, AdCampaign $campaign) {

        return $campaign->isAuthoredBy($user);
    }
}
