<?php

namespace App\Http\Controllers\Admin;

use App\Enums\EventType;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Chat\ChatRequest;
use App\Http\Resources\Admin\Conversation\MessageResource;
use App\Http\Resources\Chat\BroadcastNewMessageResource;
use App\Models\Chat;
use App\Services\Actions\EventMessageAction;
use App\Services\Actions\SendMessageAction;

class ConversationReplyController extends BaseController
{

    public function reply(Chat $chat, ChatRequest $request, SendMessageAction $sendMessageAction) {

        $message = $sendMessageAction->execute($chat, $request->getDto());

        app(EventMessageAction::class)->handler($chat, EventType::EVENT_MESSAGE, new BroadcastNewMessageResource($message));

       // broadcast(new NewMessageEvent($message))->toOthers();

        return $this->respondWithSuccess(

            new MessageResource($message)
        );
    }
}
