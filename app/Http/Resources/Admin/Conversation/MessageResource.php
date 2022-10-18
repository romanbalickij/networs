<?php

namespace App\Http\Resources\Admin\Conversation;

use App\Http\Resources\Admin\User\UserResource;
use App\Traits\Filtratable;
use Illuminate\Http\Resources\Json\JsonResource;

class MessageResource extends JsonResource
{
    use Filtratable;

    public function toArray($request)
    {
        return $this->filtrateFields([
            'id'      => $this->id,
            'text'    => $this->text,
            'created' => $this->created_at,
        ]);
    }
}
