<?php

namespace App\Models\Traits\User;

use App\Models\Notification;

trait HasNotifications
{

    public function informings() {

        return $this->hasMany(Notification::class);
    }

    public function unreadInformings() {

        return $this->hasMany(Notification::class)->where('read', false);

    }
}
