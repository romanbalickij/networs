<?php


namespace App\Services\Actions;


use App\Models\Chat;
use App\Models\Message;
use App\Services\DataTransferObjects\ChatDto;
use Illuminate\Support\Facades\Auth;

class SendMessageAction
{

    public function execute(Chat $chat, $payload) {

        //problem maria db;
        if(isset($payload['is_ppv'])) {
            $payload['is_ppv'] = $payload['is_ppv'] == 'true' ? 1 : 0;
        }

        $chat->touchLastReply();

        return Message::create(array_merge($payload, [
             'chat_id' => $chat->id,
        ]));
    }
}
