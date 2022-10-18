<?php

namespace App\Models\Traits\Chat;

use App\Models\Message;

trait HasMessages
{
    public function messages() {

        return $this->hasMany(Message::class)->orderBy('created_at', 'desc');
    }

    public function message() {

        return $this->hasOne(Message::class)->latest();
    }
}
