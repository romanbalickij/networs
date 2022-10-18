<?php

namespace App\Http\Controllers\Api;

use App\Enums\BookmarkType;
use App\Http\Controllers\BaseController;
use App\Models\Message;
use App\Services\Actions\AddBookmarkAction;

class MessageBookmarkController extends BaseController
{

    public function __invoke(Message $message, AddBookmarkAction $addBookmarkAction)
    {
        $addBookmarkAction->execute($message, BookmarkType::MODEL_MESSAGE);

        return $this->respondWithSuccess('Success');
    }
}
