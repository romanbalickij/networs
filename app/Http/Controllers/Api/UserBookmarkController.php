<?php

namespace App\Http\Controllers\Api;

use App\Enums\BookmarkType;
use App\Http\Controllers\BaseController;

use App\Models\User;
use App\Services\Actions\AddBookmarkAction;

class UserBookmarkController extends BaseController
{

    public function __invoke(User $user, AddBookmarkAction $addBookmarkAction)
    {
        $addBookmarkAction->execute($user, BookmarkType::MODEL_USER);

        return $this->respondWithSuccess('Success');
    }
}
