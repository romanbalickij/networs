<?php

namespace App\Http\Resources\Notification\Message;

use App\Http\Resources\Chat\MessageResource;
use App\Http\Resources\User\UserResource;
use App\Traits\Filtratable;
use Illuminate\Http\Resources\Json\JsonResource;

class MessageNotificationResource extends JsonResource
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
            'entity'      => new MessageResource($this->message)
        ]);
    }
}
