<?php

namespace App\Http\Controllers\Api;

use App\Enums\EventType;
use App\Enums\FileType;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Chat\ChatRequest;
use App\Http\Requests\Chat\ConversationGroupSendRequest;
use App\Http\Requests\Chat\ConversationStoreRequest;
use App\Http\Resources\Chat\BroadcastNewMessageResource;
use App\Http\Resources\Chat\ConversationCollection;
use App\Http\Resources\Chat\ConversationResource;
use App\Http\Resources\Chat\MessageCollection;
use App\Http\Resources\Chat\MessageResource;
use App\Models\Chat;
use App\Services\Actions\AttachFileAction;
use App\Services\Actions\EventMessageAction;
use App\Services\Actions\GroupSendMessageAction;
use App\Services\Actions\SendMessageAction;
use App\Services\Chat\ChatService;
use App\Services\Chat\ConversationUser;
use App\Services\Filters\ChatFilter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ConversationController extends BaseController
{
    public $chat;

    public function __construct()
    {
        $this->chat = new ChatService(new ConversationUser());
    }

    public function __invoke(ChatFilter $chatFilter, Request $request) {

        $chats = Chat::query()
            ->with([
                'user.creatorBlocking',
                'creator.creatorBlocking',
                'message.bookmarks',
                'user.subscriptions',
                'creator.subscriptions',

            ])
            ->contentCreator(user())
            ->unreadMessageCount()
            ->orderBy('updated_at', 'desc')
         //   ->filter($chatFilter)
            ->get()
            ->useCollectionfilter($request->all());

        return $this->respondWithSuccess(

            new ConversationCollection($chats)
        );
    }

    public function store(ConversationStoreRequest $request) {

       $chat = $this->chat->createRoom(user(), $request->recipient);

       return $this->respondWithSuccess(

           (new ConversationResource($chat))
       );
    }

    public function messages(Chat $chat) {

        $chat->load([
            'messages.media.entity.payments',
            'messages.others.entity.payments',
            'messages.media.bookmarks',
            'messages.others.bookmarks',
            'messages.reactions',
            'messages.bookmarks',
            'messages.payments',
            'messages' => fn($q) => $q->orderByNew()
        ]);

        return $this->respondWithSuccess(

           new MessageCollection($chat->messages)
        );
    }

    public function send(ChatRequest $request, Chat $chat, SendMessageAction $sendMessageAction, AttachFileAction $attachFileAction) {

        $message = $sendMessageAction->execute($chat, $request->getDto());


     //   $message->addAttachments($request->file('attachments'));

        $attachFileAction->execute($message->id, $request->get('attachments'), FileType::MODEL_MESSAGE);

        $message->load([
            'reactions',
            'bookmarks',
            'media.entity.payments',
            'others.entity.payments',
            'media.bookmarks',
            'others.bookmarks',
        ]);

        $to = $message->getOtherUser($message->user_id);

        app(EventMessageAction::class)->handler($chat, EventType::EVENT_MESSAGE, new BroadcastNewMessageResource($message, $to));

        return $this->respondWithSuccess(

            new MessageResource($message)
        );
    }

    public function groupSend(ConversationGroupSendRequest $request, GroupSendMessageAction $sendMessageAction) {

        $sendMessageAction->handler($request->getDto(), $request->get('attachments'), $this->chat);

        return $this->respondWithSuccess('Message sent successfully');
    }
}
