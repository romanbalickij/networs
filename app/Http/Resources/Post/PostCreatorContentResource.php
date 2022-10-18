<?php

namespace App\Http\Resources\Post;


use App\Http\Resources\Attachment\PostCreatorAttachmentCollection;
use App\Traits\Filtratable;
use Illuminate\Http\Resources\Json\JsonResource;

class PostCreatorContentResource extends JsonResource
{

    use Filtratable;

    public function toArray($request)
    {
        return $this->filtrateFields(

            array_merge((new PostResource($this))->except('media')->resolve(),
                [
                    'media'  => (new PostCreatorAttachmentCollection($this->whenLoaded('media'))),
                ]
            ));
    }
}
