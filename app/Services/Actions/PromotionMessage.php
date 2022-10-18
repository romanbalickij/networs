<?php


namespace App\Services\Actions;


use App\Enums\ChatType;
use App\Enums\EventType;
use App\Events\NewMessageEvent;
use App\Http\Resources\Chat\BroadcastNewMessageResource;
use App\Models\Chat;
use App\Notifications\NotificationHelper;
use App\Stateful\Controllers\EventController;
use Illuminate\Support\Facades\Auth;

class PromotionMessage
{
    use NotificationHelper;

    public function handler(SendMessageAction $sendMessageAction, $payload, $attachments) {

        Chat::query()
            ->where('mode', ChatType::ADMIN)
            ->chunkById(50, function($chats) use ($payload, $attachments, $sendMessageAction) {

                foreach ($chats as $chat) {

                     $message = $sendMessageAction->execute($chat, $payload);

                     $message->addAttachments($attachments);

                     app(EventMessageAction::class)->handler($chat, EventType::EVENT_MESSAGE, new BroadcastNewMessageResource($message));

                     $this->promotionNotify($chat->creator, $message);
                }
            });;

    }
}
