<?php

namespace App\Http\Resources\Chat;

use App\Traits\Filtratable;
use Illuminate\Http\Resources\Json\JsonResource;

class OneMessageResource extends JsonResource
{
    use Filtratable;

    public function toArray($request = null)
    {
        return $this->filtrateFields([
            'id'            => $this->id,
            'text'          => $this->text,
            'meta'          => $this->meta,
            'created'       => $this->created_at,
        ]);
    }

}
