<?php


namespace App\Services\Actions;


use App\Models\AccountManager;
use App\Models\Notification;
use App\Models\User;

class NotificationArrayReadAction
{

    public function execute($notifications) :void{

         Notification::whereIn('id', $notifications)
             ->each(fn($notification) => $notification->read());
    }
}
