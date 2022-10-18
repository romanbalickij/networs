<?php

namespace App\Http\Resources\Bookmark\Attachment;

use App\Http\Resources\Attachment\AttachmentResource;
use App\Traits\Filtratable;
use Illuminate\Http\Resources\Json\JsonResource;

class BookmarkAttachmentResource extends JsonResource
{
    use Filtratable;

    public function toArray($request)
    {
        return $this->filtrateFields([
            'id'          => $this->id,
            'entity_type' => $this->entity_type,
            'entity'      => new AttachmentResource($this->file)
        ]);
    }
}
