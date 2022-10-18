<?php

namespace App\Http\Resources\User;

use App\Models\Page;
use App\Traits\Filtratable;
use Illuminate\Http\Resources\Json\JsonResource;

class LandingCreatorResource extends JsonResource
{
    use Filtratable;

    public function toArray($request)
    {
        return $this->filtrateFields( [
            'id'   => $this->id,
            'main' => $this->main,
            'me'   => $this->me,
            'img'  => $this->patchImage,
        ]);
    }
}
