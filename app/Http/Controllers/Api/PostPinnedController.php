<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\BaseController;
use App\Models\Post;
use App\Services\Actions\PostPinnedAction;

class PostPinnedController extends BaseController
{

    public function __invoke(Post $post, PostPinnedAction $pinnedAction)
    {
        $pinnedAction->execute($post);

        return $this->respondWithSuccess('Success');
    }
}
