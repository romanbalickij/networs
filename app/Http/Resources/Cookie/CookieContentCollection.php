<?php

namespace App\Http\Resources\Cookie;

use Illuminate\Http\Resources\Json\ResourceCollection;

class CookieContentCollection extends ResourceCollection
{
    public $collects = CookieContentResource::class;

    public function toArray($request)
    {
        return parent::toArray($request);
    }
}
