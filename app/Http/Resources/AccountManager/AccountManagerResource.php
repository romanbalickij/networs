<?php

namespace App\Http\Resources\AccountManager;

use App\Traits\Filtratable;
use Illuminate\Http\Resources\Json\JsonResource;

class AccountManagerResource extends JsonResource
{
    use Filtratable;

    public function toArray($request)
    {
        return $this->filtrateFields([
            'id'      => $this->id,
            'name'    => $this->name,
            'surname' => $this->surname,
            'email'   => $this->email,
            'avatar'  => $this->profilePhotoUrl
        ]);
    }
}
