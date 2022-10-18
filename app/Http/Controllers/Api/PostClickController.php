<?php

namespace App\Http\Controllers\Api;

use App\Enums\EventType;
use App\Events\PostHistoryEvent;
use App\Http\Controllers\BaseController;
use App\Models\Post;
use App\Services\Actions\PostClickAction;
use Illuminate\Http\Request;

class PostClickController extends BaseController
{

    public function  __invoke(Post $post, PostClickAction $clickAction) {

        $clickAction->execute($post);

       // broadcast(new PostHistoryEvent($post, EventType::EVENT_POST_CLICK));

        return $this->respondWithSuccess('Success');
    }
}
