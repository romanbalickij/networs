<?php

namespace App\Http\Resources\Account;

use App\Traits\Filtratable;
use Illuminate\Http\Resources\Json\JsonResource;

class AccountInformationResource extends JsonResource
{
    use Filtratable;

    public function toArray($request)
    {
        return $this->filtrateFields([
            'id'                      => $this->id,
            'is_blocked'              => $this->isBanned(),
            'is_verified'             => $this->isAccountVerified(),
            'email_is_verified'       => $this->isEmailVerified(),
            'has_subscriptions'       => $this->hasSubscriptions(),
            'unread_notification'     => $this->informings_count,
            'subscriber_online_count' => $this->subscribers->reduce(fn($acc, $item) => $acc + $item->user_count)
        ]);
    }
}
