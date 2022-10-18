<?php

namespace App\Models\Traits\User;

use App\Models\Message;

trait HasMessages
{

    public function messages() {

        return $this->hasMany(Message::class);
    }

}
