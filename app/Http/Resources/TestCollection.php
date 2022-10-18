<?php

namespace App\Http\Resources;

use App\Http\Resources\Chat\MessageCollection;
use App\Http\Resources\Subscription\SubscriptionCollection;
use App\Models\Post;
use App\Models\Subscription;
use App\Traits\Filtratable;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Str;

class TestCollection extends ResourceCollection
{
    public $collects = TestResource::class;
    use Filtratable;

    public function toArray($request)
    {

        return [
          //  $this->get(Post::class)
            'interactions'   => $this->processCollection($request),
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
