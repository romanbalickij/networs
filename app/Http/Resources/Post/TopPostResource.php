<?php

namespace App\Http\Resources\Post;

use App\Traits\Filtratable;
use Illuminate\Http\Resources\Json\JsonResource;

class TopPostResource extends JsonResource
{
    use Filtratable;

    public function toArray($request)
    {
        return $this->filtrateFields([
            'id'            => $this->id,
            'text'          => $this->text,
            'interested'    => $this->interested,
            'clickthroughs' => $this->clickthroughs,
            'shows'         => $this->shows,
            'total_earned'  => $this->payments->earned(),
        ]);
    }
}
