<?php

namespace App\Http\Resources\Invoice;

use App\Http\Controllers\Api\UserController;
use App\Http\Resources\User\UserResource;
use App\Traits\Filtratable;
use Illuminate\Http\Resources\Json\JsonResource;

class InvoiceResource extends JsonResource
{
    use Filtratable;

    public function toArray($request)
    {
        return $this->filtrateFields([
            'id'                    => $this->id,
            'type'                  => $this->type,
            'sum'                   => $this->sum,
            'direction'             => $this->direction,
            'purpose_string'        => $this->purpose_string,
            'commission'            => $this->commission_sum,
            'transaction_crypto_id' => $this->transaction_crypto_id,
            'crypto_type'           => $this->crypto_type,
            'status'                => $this->status,

            'counterparty'   => $this->when($this->user_id, collect(

               $this->user_id ? (new UserResource($this->user))->only('id', 'name', 'surname') : []

            )->merge(['link' => $url = action([UserController::class, 'show'], ['user' => $this->user->id ?? 0])]))
        ]);
    }
}
