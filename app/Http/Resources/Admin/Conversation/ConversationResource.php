<?php

namespace App\Http\Resources\Admin\Conversation;

use App\Http\Resources\Admin\User\UserResource;
use App\Traits\Filtratable;
use Illuminate\Http\Resources\Json\JsonResource;

class ConversationResource extends JsonResource
{

    use Filtratable;

    public function toArray($request)
    {
        return $this->filtrateFields([
            'id'       => $this->id,

            'message'  => (new MessageResource($this->whenLoaded('message')))->only('id', 'text', 'created'),
            'user'     => (new UserResource($this->whenLoaded('creator')))->only('id', 'name', 'surname', 'avatar', 'is_online') // Contreparty user
        ]);
    }
}
