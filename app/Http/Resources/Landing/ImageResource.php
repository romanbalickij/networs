<?php

namespace App\Http\Resources\Landing;

use App\Traits\Filtratable;
use Illuminate\Http\Resources\Json\JsonResource;

class ImageResource extends JsonResource
{
    use Filtratable;

    public function toArray($request)
    {
        return $this->filtrateFields([
            'id'   => $this->id,
            'name' => $this->name,
            'url'  => $this->url
        ]);
    }
}
