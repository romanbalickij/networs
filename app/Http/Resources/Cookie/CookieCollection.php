<?php

namespace App\Http\Resources\Cookie;

use Illuminate\Http\Resources\Json\ResourceCollection;

class CookieCollection extends ResourceCollection
{

    public $collects = CookieResource::class;

    public function toArray($request)
    {
        return parent::toArray($request);
    }
}
