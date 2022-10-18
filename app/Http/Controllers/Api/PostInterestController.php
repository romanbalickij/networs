<?php

namespace App\Http\Controllers\Api;

use App\Enums\EventType;
use App\Events\PostHistoryEvent;
use App\Http\Controllers\BaseController;
use App\Models\Post;
use App\Services\Actions\PostInterestAction;
use Illuminate\Http\Request;

class PostInterestController extends BaseController
{
    public function __invoke(Post $post, PostInterestAction $postInterestAction) {

        $postInterestAction->execute($post);

       // broadcast(new PostHistoryEvent($post, EventType::EVENT_POST_INTEREST));

        return $this->respondWithSuccess('Success');
    }
}
