<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;

use App\Models\Chat;
use App\Services\Chat\ChatService;
use App\Services\Chat\ConversationSupport;
use Illuminate\Http\Request;

class ConversationJoinController extends BaseController
{
    public $chat;

    public function __construct()
    {
        $this->chat = new ChatService(new ConversationSupport());
    }
    public function join(Chat $chat) {

        $this->chat->join($chat, user()->id);

        return $this->respondWithSuccess('Success');
    }
}
