<?php

namespace App\Http\Resources\User;

use App\Enums\ManagedAccountType;
use App\Traits\Filtratable;
use Illuminate\Http\Resources\Json\JsonResource;

class ManagerAccountResource extends JsonResource
{
    use Filtratable;

    public function toArray($request)
    {
      return $managers = $this->accountManagers
          ->map(fn ($man) => array_merge(
              (new UserResource($man->owner))->only('id', 'name', 'surname', 'avatar', 'nickname')->resolve(), ['type' => ManagedAccountType::MANAGED, 'is_active' => $this->isActiveCurrentAccount($man->owner->id)])
          )
          ->push((object)[
            'id'        => $this->id,
            'name'      => $this->name,
            'surname'   => $this->surname,
             'nickname' => $this->nickname,
            'avatar'    => $this->profilePhotoUrl,
            'type'      => ManagedAccountType::OWNER,
            'is_active' => !$this->current_account
        ])->toArray();
    }

}
