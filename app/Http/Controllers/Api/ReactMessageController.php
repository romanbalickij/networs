<?php

namespace App\Http\Controllers\Api;

use App\Enums\EventType;
use App\Enums\NotificationType;
use App\Events\ReactMessageEvent;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Chat\ReactMessageRequest;
use App\Http\Resources\Chat\BroadcastNewMessageResource;
use App\Http\Resources\Even\NewNotificationResource;
use App\Http\Resources\User\UserResource;
use App\Models\Message;
use App\Notifications\ReactionMailNotification;
use App\Services\Actions\EventMessageAction;
use App\Services\Actions\NewNotificationAction;
use App\Services\Actions\ReactMessageAction;
use App\Stateful\Controllers\EventController;
use Illuminate\Support\Facades\Auth;


class ReactMessageController extends BaseController
{
    public function __invoke(ReactMessageRequest $request, Message $message, ReactMessageAction $reactMessageAction, NewNotificationAction $newNotificationAction) {

        $reactMessageAction->execute($message, $request->reactMessagePayload());

        $notification = $newNotificationAction->execute($message, NotificationType::REACTION_MESSAGE, $message->owner, user());

//
//        app(EventMessageAction::class)->handler($message->chat, EventType::EVENT_REACTION_MESSAGE,
//            [
//                'message_id'  => $message->id,
//                'reaction'    => $request->reaction,
//                'sender'      => (new UserResource(user()))->only('id', 'name', 'surname', 'avatar', 'nickname')
//            ]
//        );


        $message->owner->notify(
            (new ReactionMailNotification($notification))->locale($message->owner->locale)
        );

        return $this->respondOk('The reaction  added successfully');
    }
}
