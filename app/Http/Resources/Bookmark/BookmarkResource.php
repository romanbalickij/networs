<?php

namespace App\Http\Resources\Bookmark;

use App\Enums\BookmarkType;
use App\Http\Resources\Bookmark\Attachment\BookmarkAttachmentResource;
use App\Http\Resources\Bookmark\Message\BookmarkMessageResource;
use App\Http\Resources\Bookmark\Post\BookmarkPostResource;
use App\Http\Resources\Bookmark\User\BookmarkUserResource;
use Illuminate\Http\Resources\Json\JsonResource;

class BookmarkResource extends JsonResource
{

    public function toArray($request)
    {
        return  [
            $this->mergeWhen($this->entity_type == BookmarkType::MODEL_POST, new BookmarkPostResource($this) ),
            $this->mergeWhen($this->entity_type == BookmarkType::MODEL_MESSAGE, new BookmarkMessageResource($this) ),
            $this->mergeWhen($this->entity_type == BookmarkType::MODEL_ATTACHMENT, new BookmarkAttachmentResource($this) ),
            $this->mergeWhen($this->entity_type == BookmarkType::MODEL_USER, new BookmarkUserResource($this) ),
        ];
    }
}
