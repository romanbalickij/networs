<?php

namespace App\Http\Resources\Admin\Invoice;

use App\Http\Resources\Admin\User\UserResource;
use App\Traits\Filtratable;
use Illuminate\Http\Resources\Json\JsonResource;

class PlatformInvoiceResource extends JsonResource
{
    use Filtratable;

    public function toArray($request)
    {
        return $this->filtrateFields([
            'id'             => $this->id,
            'direction'      => $this->direction,
            'sum'            => $this->sum,
            'type'           => $this->type,
            'purpose_string' => $this->purpose_string,
            'commission'     => $this->commission_sum,
            'created'        => $this->created_at,
            'creator'        => (new UserResource($this->owner))->only('id', 'name', 'surname')
        ]);
    }
}
