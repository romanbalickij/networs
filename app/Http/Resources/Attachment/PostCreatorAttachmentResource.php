<?php

namespace App\Http\Resources\Attachment;

use App\Traits\Filtratable;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class PostCreatorAttachmentResource extends JsonResource
{
    use Filtratable;

    public function toArray($request)
    {
        return $this->filtrateFields([
            'id'        => $this->id,
            'mime_type' => $this->type,
            'name'      => $this->name,
            'url'       => $this->postPathFile($this->entity->user, Auth::user()),
            'poster'    => $this->postPathPosterFile($this->entity->user, Auth::user()),
            'is_bookmarked' => $this->whenLoaded('bookmarks', fn() => $this->isBookmarked()),
        ]);
    }
}
