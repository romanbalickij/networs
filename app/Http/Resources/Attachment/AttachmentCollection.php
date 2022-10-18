<?php

namespace App\Http\Resources\Attachment;

use App\Traits\Filtratable;
use Illuminate\Http\Resources\Json\ResourceCollection;

class AttachmentCollection extends ResourceCollection
{
    use Filtratable;

    public $collects = AttachmentResource::class;

    public function toArray($request)
    {
        return $this->processCollection($request);
    }
}
