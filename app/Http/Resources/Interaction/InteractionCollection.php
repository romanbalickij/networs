<?php

namespace App\Http\Resources\Interaction;

use App\Traits\Filtratable;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Str;

class InteractionCollection extends ResourceCollection
{
    use Filtratable;

    public $collects = InteractionResource::class;

    public function toArray($request)
    {
        return [
            'interactions' => $this->processCollection($request),
            'pagination'   => $this->pagination()
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
