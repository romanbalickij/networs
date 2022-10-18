<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use App\Http\Resources\Admin\Conversation\MessageCollection;
use App\Models\Chat;
use Illuminate\Http\Request;

class ConversationMessageController extends BaseController
{

    public function __invoke(Chat $chat) {

        return $this->respondWithSuccess(

            new MessageCollection($chat->messages)
        );
    }
}
