<?php

namespace App\Http\Resources\Donation;

use App\Traits\Filtratable;
use Illuminate\Http\Resources\Json\JsonResource;

class DonationResource extends JsonResource
{
    use Filtratable;

    public function toArray($request)
    {
        return $this->filtrateFields([
            'id'  => $this->id,
            'sum' => $this->sum,
        ]);
    }
}
