<?php

namespace App\Http\Resources\User;

use Illuminate\Http\Resources\Json\JsonResource;

class BlockingUserResource extends JsonResource
{


    public function toArray($request)
    {

        return [
            'id'       => $this->user->id,
            'name'     => $this->user->name,
            'username' => $this->user->surname,
            'avatar'   => $this->user->profilePhotoUrl,
        ];
    }
}
