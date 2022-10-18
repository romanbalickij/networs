<?php

namespace App\Models\Traits\Message;

use App\Enums\NotificationType;
use App\Models\Notification;

trait HasNotifications
{

    public function informings() {

        return $this
            ->hasMany(Notification::class,'entity_id')
            ->where('entity_type',NotificationType::UNREAD_MESSAGES);
    }

}
