<?php


namespace App\Services\Actions;


use App\Models\ReferralLink;

class AddReferralAction
{

    public function execute($data) {

       return ReferralLink::create($data);
    }
}
