<?php

namespace App\Http\Resources\Attachment;

use App\Traits\Filtratable;
use Illuminate\Http\Resources\Json\ResourceCollection;

class PostCreatorAttachmentCollection extends ResourceCollection
{
    use Filtratable;

    public $collects = PostCreatorAttachmentResource::class;

    public function toArray($request)
    {
        return $this->processCollection($request);
    }
}
