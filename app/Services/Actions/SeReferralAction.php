<?php


namespace App\Services\Actions;

use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Crypt;

class SeReferralAction
{

    public function handle($user) {

        if(Cookie::get('referral') == null) {
            return;
        }

        $this->setReferralToUser($user, Cookie::get('referral'));
    }

    protected function setReferralToUser($user, $referralHas) {

        $user->update(['referral_link_id' => Crypt::decrypt($referralHas)]);
    }
}
