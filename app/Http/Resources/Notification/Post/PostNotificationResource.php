<?php

namespace App\Http\Resources\Notification\Post;

use App\Http\Resources\Post\PostResource;
use App\Http\Resources\User\UserResource;
use App\Traits\Filtratable;
use Illuminate\Http\Resources\Json\JsonResource;

class PostNotificationResource extends JsonResource
{
    use Filtratable;

    public function toArray($request)
    {
        return $this->filtrateFields([
            'id'          => $this->id,
            'entity_type' => $this->entity_type,
            'read'        => $this->read,
            'created'     => $this->updated_at,
            'sender'      => (new UserResource($this->sender))->only('id', 'name', 'surname', 'avatar', 'nickname'),
            'entity'      => new PostResource($this->post)
        ]);
    }
}
