<?php

namespace App\Http\Resources\Bookmark\User;


use App\Http\Resources\User\UserResource;
use App\Traits\Filtratable;
use Illuminate\Http\Resources\Json\JsonResource;

class BookmarkUserResource extends JsonResource
{
    use Filtratable;

    public function toArray($request)
    {
        return $this->filtrateFields([
            'id'          => $this->id,
            'entity_type' => $this->entity_type,
            'entity'      => new UserResource($this->user)
        ]);
    }
}
