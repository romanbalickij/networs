<?php

namespace App\Http\Resources\Chat;

use App\Http\Resources\User\UserResource;
use App\Traits\Filtratable;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class ConversationResource extends JsonResource
{
    use Filtratable;

    public function toArray($request)
    {
        return $this->filtrateFields([
            'id'                    => $this->id,
            'is_support'            => $this->isSupport(),
            'created'               => $this->created_at,
            'unread_messages_count' => $this->unread_messages_count,
            'i_am_blocked'          => $this->when(Auth::check() and !$this->isSupport(), fn() => $this->contrepartyUser()->isBlockedUser(user())),

            'message'               =>  new OneMessageResource($this->message),
            'user'                  => (new UserResource($this->contrepartyUser()))->only('id', 'name', 'surname', 'nickname', 'avatar', 'verified', 'is_online'),
        ]);
    }
}
