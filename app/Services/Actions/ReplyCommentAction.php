<?php

namespace App\Services\Actions;

use App\Enums\InteractionType;
use App\Models\Comment;
use App\Models\Post;
use App\Models\User;

class ReplyCommentAction
{


    public function execute(Comment $comment, Post $post, $content) {

        $reply = $comment->addReplies(array_merge($content, [
            'post_id' => $post->id
        ]));

        $reply->pushToInteractions(InteractionType::TYPE_RESPONSE_COMMENT, User::find($comment->user_id));

        return $reply;
    }
}
