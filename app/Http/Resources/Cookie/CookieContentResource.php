<?php

namespace App\Http\Resources\Cookie;

use Illuminate\Http\Resources\Json\JsonResource;

class CookieContentResource extends JsonResource
{

    public function toArray($request)
    {
        return [
            'id'                 => $this->id,
            'title'              => $this->title,
            'body'               => $this->body,
            'description'        => $this->description,
            'footer_description' => $this->footer_description,
            'key'                => $this->key
        ];
    }
}
