<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;


class SettingResource extends JsonResource
{

    public function toArray($request)
    {
        return [
            $this->key => is_numeric($this->value) ? (boolean) $this->value : $this->value,
        ];
    }
}
