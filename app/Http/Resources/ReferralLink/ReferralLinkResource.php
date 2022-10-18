<?php

namespace App\Http\Resources\ReferralLink;

use App\Traits\Filtratable;
use Illuminate\Http\Resources\Json\JsonResource;

class ReferralLinkResource extends JsonResource
{
   use Filtratable;

    public function toArray($request)
    {
        return $this->filtrateFields([
            'id'           => $this->id,
            'name'         => $this->name,
            'total_earned' => $this->users->sumReferralTotalEarned(),
            'total_users'  => $this->users_count,
        ]);
    }
}
