<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Post\PostStatisticRequest;
use App\Models\Post;
use App\Services\Actions\PostStatisticAction;


class PostStatisticController extends BaseController
{

    public function __invoke(PostStatisticRequest $request, Post $post, PostStatisticAction $postStatisticAction) {

        return $this->respondWithSuccess(

            $postStatisticAction->handler($post, $request->get('from'), $request->get('to'))
        );
    }
}
