<?php

namespace App\Http\Controllers\Api;

use App\Enums\BookmarkType;
use App\Http\Controllers\BaseController;
use App\Models\Post;
use App\Services\Actions\AddBookmarkAction;


class PostBookmarkController extends BaseController
{

    public function __invoke(Post $post, AddBookmarkAction $addBookmarkAction)
    {
        $addBookmarkAction->execute($post, BookmarkType::MODEL_POST);

        return $this->respondWithSuccess('Success');
    }
}
