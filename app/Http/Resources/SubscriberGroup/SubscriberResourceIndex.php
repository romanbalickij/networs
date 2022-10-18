<?php

namespace App\Http\Resources\SubscriberGroup;

use App\Http\Resources\User\UserResource;
use App\Traits\Filtratable;
use Illuminate\Http\Resources\Json\JsonResource;

class SubscriberResourceIndex extends JsonResource
{
    use Filtratable;

    public function toArray($request)
    {
        return $this->filtrateFields([
            'id'     => $this->id,
            'user'   => (new UserResource($this->user))->only('id', 'name', 'surname','avatar', 'nickname', 'description', 'is_online'),
        ]);
    }
}
