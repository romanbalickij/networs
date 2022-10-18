<?php


namespace App\Services\Actions;


use App\Models\AccountManager;
use App\Models\Message;
use App\Models\Notification;
use App\Models\User;

class MessageArrayReadAction
{

    public function execute($messages) :void{

         Message::whereIn('id', $messages)
             ->each(fn($message) => app(MessageReadAction::class)->execute($message));
    }
}
