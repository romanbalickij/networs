<?php

namespace App\Http\Resources\User;

use App\Traits\Filtratable;
use Illuminate\Http\Resources\Json\JsonResource;

class PlanResource extends JsonResource
{
     use Filtratable;

    public function toArray($request)
    {
        return $this->filtrateFields([
            'id'           => $this->id,
            'monthly_cost' => $this->monthly_cost,
            'discount'     => $this->discount,
            'name'         => $this->name,
            'description'  => $this->description,
            'is_subscribed'=> $this->IsAuthUserSubscriberPlan
        ]);
    }
}
