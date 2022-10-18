<?php

namespace App\Http\Resources\Attachment;

use App\Traits\Filtratable;
use Illuminate\Http\Resources\Json\JsonResource;

class AttachmentResource extends JsonResource
{
    use Filtratable;

    public function toArray($request)
    {
        return $this->filtrateFields([
            'id'        => $this->id,
            'mime_type' => $this->type,
            'name'      => $this->name,
            'url'       => $this->patchFile,
            'poster'    => $this->patchPoster,
            'is_bookmarked' => $this->whenLoaded('bookmarks', fn() => $this->isBookmarked()),
        ]);
    }
}
