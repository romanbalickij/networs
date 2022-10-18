<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use App\Http\Resources\Admin\Conversation\ConversationCollection;
use App\Models\Chat;
use App\Services\Filters\ChatFilter;
use Illuminate\Http\Request;

class ConversationOpenIssueController extends BaseController
{

    public function open(ChatFilter $chatFilter) {

        $chats = Chat::with(['message', 'creator'])
            ->support()
            ->filter($chatFilter)
            ->get();

        return $this->respondWithSuccess(

            new ConversationCollection($chats)
        );
    }
}
