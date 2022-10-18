<?php

namespace App\Services\Builders;

use Illuminate\Database\Eloquent\Builder;

class NotificationBuilder extends Builder
{

    public function unread() {

        return $this->where('read', false);
    }
}
