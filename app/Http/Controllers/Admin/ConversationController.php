<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use App\Http\Resources\Admin\Conversation\ConversationCollection;
use App\Models\Chat;
use App\Services\Chat\ChatService;
use App\Services\Chat\ConversationSupport;
use App\Services\Filters\ChatFilter;
use Illuminate\Http\Request;

class ConversationController extends BaseController
{
    public $chat;

    public function __construct()
    {
        $this->chat = new ChatService(new ConversationSupport());
    }

    public function current(ChatFilter $chatFilter) {

      $chats = Chat::with(['message', 'creator'])
          ->contentAdmin(user())
          ->filter($chatFilter)
          ->get();

      return $this->respondWithSuccess(

          new ConversationCollection($chats)
      );
  }

  public function resign(Chat $chat) {

      $this->chat->leave($chat);

      return $this->respondWithSuccess('Success');
  }
}
