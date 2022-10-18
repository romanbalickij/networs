<?php

namespace App\Services\Builders;

use App\Enums\ChatType;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;

class ChatBuilder extends Builder
{

    public function whereRecipients($user, $currentUser) {

       return $this->where('service_id', $user)
            ->where('client_id', $currentUser)
            ->orWhere(fn ($q) => $q->where('client_id', $user) ->where('service_id', $currentUser));
    }

    public function unreadMessageCount() {

        return $this->withCount(['messages as unread_messages_count' => fn($query) => $query->isNotMy()->unread()]);
    }

    public function support() {

        return $this->where('service_id', NULL);
    }


    public function contentCreator(User $user) {

        return $this->where(function ($q) use($user){
            return $q
                ->where('client_id', $user->id)
                ->orWhere('service_id', $user->id);
        });
    }

    public function contentAdmin(User $user) {

        return $this
            ->where('service_id', $user->id)
            ->where('mode', ChatType::ADMIN);
    }

    public function filter($filters) {

        return $filters->apply($this);
    }
}
