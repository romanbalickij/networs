<?php

namespace App\Http\Resources\Comment;

use App\Traits\Filtratable;
use Illuminate\Http\Resources\Json\ResourceCollection;

class CommentCollection extends ResourceCollection
{

    use Filtratable;

    public $collects = CommentResource::class;

    public function toArray($request)
    {
        return $this->processCollection($request);
    }
}
