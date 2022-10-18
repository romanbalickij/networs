<?php

namespace App\Http\Resources\Comment;

use App\Http\Resources\User\UserResource;
use App\Traits\Filtratable;
use Illuminate\Http\Resources\Json\JsonResource;

class CommentResource extends JsonResource
{
     use Filtratable;

    public function toArray($request)
    {
        return $this->filtrateFields([
            'id'             => $this->id,
            'content'        => $this->content,
            'created'        => $this->created_at,
            'responds_to_id' => $this->responds_to_id,
            'moderated'      => $this->moderated,
            'post_id'        => $this->post_id,
            'author'         => (new UserResource($this->user))->only('id', 'name', 'surname', 'avatar', 'nickname'),
            'replies'        => new CommentCollection($this->replies)
        ]);
    }
}
