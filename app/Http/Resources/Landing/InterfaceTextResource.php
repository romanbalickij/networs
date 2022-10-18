<?php

namespace App\Http\Resources\Landing;

use App\Traits\Filtratable;
use Illuminate\Http\Resources\Json\JsonResource;

class InterfaceTextResource extends JsonResource
{

    use Filtratable;

    public function toArray($request)
    {
        return $this->filtrateFields([
            'id'           => $this->id,
            'key'          => $this->key,
            'name'         => $this->name,
            'length_limit' => $this->length_limit,
            'text'         => $this->text,
            'images'       => new ImageCollection($this->images),
        ]);
    }
}
