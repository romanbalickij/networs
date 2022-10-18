<?php

namespace App\Http\Resources\Post;

use App\Traits\Filtratable;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Str;

class PostCollection extends ResourceCollection
{
    use Filtratable;

    public $collects = PostResource::class;

    public function toArray($request)
    {
        return [
            'posts'      => $this->processCollection($request),
            'pagination' => $this->pagination()
        ];
    }


    public function pagination() {

        return [
          //   'last_page'      => $this->onLastPage(),
             'per_page'   => $this->perPage(),
             'next_page'  => Str::of($this->nextPageUrl())->after('page='),

        ];
    }

    public function toResponse($request)
    {
        return JsonResource::toResponse($request);
    }
}
