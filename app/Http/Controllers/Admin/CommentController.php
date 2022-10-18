<?php

namespace App\Http\Controllers\Admin;

use App\Enums\NotificationType;
use App\Http\Controllers\BaseController;
use App\Models\Comment;
use App\Notifications\CommentModeratedMailNotification;
use App\Services\Actions\NewNotificationAction;

class CommentController extends BaseController
{

    public function moderate(Comment $comment, NewNotificationAction $newNotificationAction) {

        $comment->toggle();

        $newNotificationAction->execute($comment, NotificationType::ACCOUNT_COMMENT_MODERATED, $comment->user, user());

        $comment->user->notify(
            (new CommentModeratedMailNotification($comment))->locale($comment->user->locale)
        );

        return $this->respondWithSuccess('Ok');
    }
}
