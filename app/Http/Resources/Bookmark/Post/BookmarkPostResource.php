<?php

namespace App\Http\Resources\Bookmark\Post;

use App\Http\Resources\Post\PostResource;
use App\Traits\Filtratable;
use Illuminate\Http\Resources\Json\JsonResource;

class BookmarkPostResource extends JsonResource
{
     use Filtratable;

    public function toArray($request)
    {
        return $this->filtrateFields([
            'id'          => $this->id,
            'entity_type' => $this->entity_type,
            'entity'      => (new PostResource($this->post))
                ->only('id', 'text', 'media', 'others', 'is_ppv', 'is_pay', 'ppv_price', 'is_me', 'ppv_earned', 'ppv_user_paid')
        ]);
    }
}
