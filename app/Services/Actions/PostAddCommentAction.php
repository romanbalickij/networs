<?php

namespace App\Services\Actions;

use App\Enums\InteractionType;
use App\Models\Post;

class PostAddCommentAction
{

    public function execute(Post $post, $comment) {

        $comment = $post->addComment($comment);

        $comment->pushToInteractions(InteractionType::TYPE_COMMENTING, user());

        return $comment;
    }
}
