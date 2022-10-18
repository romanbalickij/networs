<?php

namespace App\Http\Resources\Cookie;

use Illuminate\Http\Resources\Json\JsonResource;

class CookieResource extends JsonResource
{

    public function toArray($request)
    {
        return [
            'id'       => $this->id,
            'name'     => $this->name,
            'provider' => $this->provider,
            'purpose'  => $this->purpose,
            'expiry'   => $this->expiry,
            'type'     => $this->type,
            'key'      => $this->key
        ];
    }
}
