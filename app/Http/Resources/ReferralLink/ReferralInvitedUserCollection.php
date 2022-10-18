<?php

namespace App\Http\Resources\ReferralLink;

use App\Traits\Filtratable;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ReferralInvitedUserCollection extends ResourceCollection
{
    use Filtratable;

    public $collects = ReferralInvitedUserResource::class;

    public function toArray($request)
    {
        return $this->processCollection($request);
    }
}
