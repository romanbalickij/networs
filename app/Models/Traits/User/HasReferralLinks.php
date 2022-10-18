<?php

namespace App\Models\Traits\User;

use App\Models\ReferralLink;

trait HasReferralLinks
{

    public function referralLinks() {

        return $this->hasMany(ReferralLink::class);
    }

    public function referral() {

        return $this->belongsTo(ReferralLink::class, 'referral_link_id')->withDefault();
    }

    public function referralPartner() {

        return $this->referral->user;
    }
}
