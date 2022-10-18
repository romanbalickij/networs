<?php

namespace App\Http\Resources\ReferralLink;

use App\Traits\Filtratable;
use Illuminate\Http\Resources\Json\JsonResource;

class ReferralInvitedUserResource extends JsonResource
{
    use Filtratable;

    public function toArray($request)
    {
        return $this->filtrateFields([
            'id'           => $this->id,
            'name'         => $this->name,
            'surname'      => $this->surname,
            'avatar'       => $this->profilePhotoUrl,
            'total_earned' => (float) $this->invoices_sum_sum
        ]);
    }
}
