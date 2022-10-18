<?php


namespace App\Services\Actions;

use App\Stateful\Controllers\EventController;
use Illuminate\Support\Facades\Auth;

class EventMessageAction
{

    public function handler($chat, $eventType, $content) {

        $usersId = collect([$chat->client_id, $chat->service_id]);

        $to = $usersId->filter(fn($id) => $id != Auth::id())->first();

//        $to = $chat->otherUser(Auth::id());

        EventController::trigger($to, $eventType, ['content' => $content]);
    }
}
