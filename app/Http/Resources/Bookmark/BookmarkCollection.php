<?php

namespace App\Http\Resources\Bookmark;

use App\Traits\Filtratable;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Str;

class BookmarkCollection extends ResourceCollection
{

    use Filtratable;

    public $collects = BookmarkResource::class;

    public function toArray($request)
    {
        return [
            'bookmarks'     => $this->processCollection($request),
            'pagination'    => $this->pagination()
        ];
    }

    public function pagination() {

        return [
            'per_page'   => $this->perPage(),
            'next_page'  => Str::of($this->nextPageUrl())->after('cursor=')
        ];
    }

    public function toResponse($request)
    {
        return JsonResource::toResponse($request);
    }
}
