<?php

namespace App\Http\Resources\Admin\User;

use App\Traits\Filtratable;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    use Filtratable;

    public function toArray($request)
    {
        return $this->filtrateFields([
            'id'          => $this->id,
            'email'       => $this->email,
            'name'        => $this->name,
            'surname'     => $this->surname,
            'created'     => $this->created_at,
            'verified'    => $this->verified,
            'blocked'     => $this->blocked,
            'subscribers' => $this->subscribers_count,
            'avatar'      => $this->profilePhotoUrl,
            'is_online'   => $this->isOnline(),
        ]);
    }
}
