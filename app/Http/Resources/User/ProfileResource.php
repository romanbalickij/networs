<?php

namespace App\Http\Resources\User;

use App\Traits\Filtratable;
use Illuminate\Http\Resources\Json\JsonResource;

class ProfileResource extends JsonResource
{

    use Filtratable;


    public function toArray($request)
    {
        return $this->filtrateFields(

            array_merge((new UserResource($this))->only(...array_merge($this->getFillable(), ['nickname']))->resolve(),
            [
                'id'                  => $this->id,
                'balance'             => $this->balance,
                'tax_number'          => $this->tax_number,
                'business_address'    => $this->business_address,
                'referral_link_id'    => $this->referral_link_id,
                'my_rating'           => $this->myRating(),
                'can_send_broadcasts' => $this->isNotBanned(),
                'verified_payment'    => $this->isVerifieldPaymentAccount(),
                'ui_prompts'          => $this->ui_prompts,

                'managed_accounts'    => (new ManagerAccountResource($this)),


            ]
        ));
    }
}
