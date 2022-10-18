<?php

namespace App\Http\Resources\Bookmark\Message;

use App\Http\Resources\Chat\MessageResource;
use App\Traits\Filtratable;
use Illuminate\Http\Resources\Json\JsonResource;

class BookmarkMessageResource extends JsonResource
{
    use Filtratable;

    public function toArray($request)
    {
        return $this->filtrateFields([
            'id'          => $this->id,
            'entity_type' => $this->entity_type,
            'entity'      => new MessageResource($this->message)
        ]);
    }
}
