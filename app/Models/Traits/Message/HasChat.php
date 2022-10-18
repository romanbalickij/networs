<?php

namespace App\Models\Traits\Message;

use App\Models\Chat;

trait HasChat
{

    public function chat() {

        return $this->belongsTo(Chat::class);
    }
}
