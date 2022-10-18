<?php


namespace App\Stateful\Behaviours;


use App\Enums\EventType;
use App\Http\Resources\Chat\BroadcastNewMessageResource;
use App\Http\Resources\Chat\MessageResource;
use App\Models\Chat;
use App\Services\Actions\AttachFileAction;
use App\Services\Actions\SendMessageAction;
use App\Services\DataTransferObjects\ChatDto;
use App\Stateful\Behaviour;
use App\Stateful\EventProtocol;

/**
 * Class HandlesMessageReads
 * @package App\Stateful\Behaviours
 *
 *
 * Request:
 * {
 *   chat_id: 12345,
 *   text: "hello",
 *   is_ppv: false,
 *   ppv_price: 11
 * }
 *
 */
class ReceivesTextMessages extends Behaviour
{

    public function on (): array
    {
        return [EventType::EVENT_FROM_FRONTEND_SEND_TEXT_MESSAGE];
    }

    public function triggered (EventProtocol $protocol)
    {
        $payload = $protocol->getPayload();

//        if (isset($payload['attachment']) && $payload['attachment'])
//            throw new \Exception('Attachments are not supported through websockets');

        $chat = Chat::findOrFail($protocol->getPayload()['chat_id']);

        $filtered = ChatDto::fromArray($payload)->toArray();
        $filtered['user_id'] = $protocol->getUidSender();

        $message = app(SendMessageAction::class)->execute($chat, $filtered);

        app(AttachFileAction::class)->execute($message->id, $payload['attachments'] ?? []);

        $message->load([
            'media.entity.payments',
            'others.entity.payments',
        ]);

        $this->broadcast(new EventProtocol(
            $message->getOtherUser($protocol->getUidSender()),
            'message_send',
            (new BroadcastNewMessageResource($message))->toArray()
        ));

        $this->broadcast(new EventProtocol(
            $protocol->getUidSender(),
            'message_send',
            array_merge(
                (new BroadcastNewMessageResource($message))->toArray(),
                [
                    'is_me' => true,
                    'sender' => []
                ]
            )
        ));
    }
}
