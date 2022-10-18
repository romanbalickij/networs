<?php

namespace App\Models\Traits\User;

use App\Enums\ChatType;
use App\Models\Chat;
use Illuminate\Support\Facades\Auth;

trait HasChats
{

    // carefully does not work  when use with or load
    public function chats() {

        return $this->hasMany(Chat::class, 'client_id')
            ->orWhere('service_id', $this->userId());
    }

    public function chat() {

        return $this->serviceFor(Auth::id());
    }

    public function support() {

        return $this->hasOne(Chat::class, 'client_id')
            ->where('mode', ChatType::ADMIN)
            ->latest('updated_at');
    }

    public function chatFor(?int $user) {

        return $this->serviceFor($user)->first();
    }

    protected function serviceFor(?int $user) {

        return $this->hasOne(Chat::class,'client_id')
            ->whereRecipients($user, $this->id);

    }

}
