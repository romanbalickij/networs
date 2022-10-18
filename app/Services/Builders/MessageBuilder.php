<?php

namespace App\Services\Builders;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class MessageBuilder extends Builder
{

    public function unread() {

        return $this->where('read', false);
    }

    public function isNotMy() {

        return $this->where('user_id', '!=', Auth::id());
    }

    public function orderByNew() {

        return $this->orderBy('created_at', 'desc');
    }

    public function notYetSentToMail() {

        return $this->where('send_email', false);
    }

}
