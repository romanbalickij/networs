<?php

namespace App\Http\Resources\Post;

use App\Traits\Filtratable;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Str;

class TopPostCollection extends ResourceCollection
{
    use Filtratable;

    public $collects = TopPostResource::class;

    public function toArray($request)
    {
        return [
            'posts'      => $this->processCollection($request),
            'pagination' => $this->pagination()
        ];
    }

    public function pagination() {

        return [
            'per_page'   => $this->perPage(),
            'next_page'  => Str::of($this->nextPageUrl())->after('page=')
        ];
    }

    public function toResponse($request)
    {
        return JsonResource::toResponse($request);
    }
}
