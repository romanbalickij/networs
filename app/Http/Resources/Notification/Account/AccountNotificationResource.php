<?php

namespace App\Http\Resources\Notification\Account;

use App\Http\Resources\User\UserResource;
use App\Traits\Filtratable;
use Illuminate\Http\Resources\Json\JsonResource;

class AccountNotificationResource extends JsonResource
{
    use Filtratable;

    public function toArray($request)
    {
        return $this->filtrateFields([
            'id'          => $this->id,
            'entity_type' => $this->entity_type,
            'read'        => $this->read,
            'created'     => $this->updated_at,
            'sender'      => (new UserResource($this->sender))->only('id', 'name', 'surname', 'avatar', 'nickname'),
            'entity'      => (new UserResource($this->account))->only('id', 'name', 'surname', 'avatar', 'nickname'),
        ]);
    }
}
