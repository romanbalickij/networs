<?php

namespace App\Http\Controllers\Api;

use App\Enums\NotificationType;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Comment\CommentRequest;
use App\Http\Resources\Comment\CommentCollection;
use App\Http\Resources\Comment\CommentResource;
use App\Models\Comment;
use App\Models\Post;
use App\Notifications\CommentMailNotification;
use App\Services\Actions\NewNotificationAction;
use App\Services\Actions\PostAddCommentAction;
use App\Services\Actions\ReplyCommentAction;
use Illuminate\Support\Facades\Auth;


class PostCommentController extends BaseController
{

    public function __invoke(Post $post) {

        $post->load([
            'comments.user',
            'comments.replies.user',
            'comments'
        ]);

        return $this->respondWithSuccess(

            new CommentCollection($post->getComments())
        );
    }

    public function store(CommentRequest $request, Post $post, PostAddCommentAction $addCommentAction, NewNotificationAction $newNotificationAction) {

          $comment = $addCommentAction->execute($post, $request->commentPayload());

          if($post->user_id != Auth::id()) {
              $newNotificationAction->execute($comment, NotificationType::COMMENT, $post->user, user());

              $post->user->notify(
                  (new CommentMailNotification($comment, $post->user))->locale($post->user->locale)
              );
          }

          return $this->respondWithSuccess(

              new CommentResource($comment)
          );
    }

    public function reply(CommentRequest $request, Post $post, Comment $comment, ReplyCommentAction $replyCommentAction, NewNotificationAction $newNotificationAction ) {

        $reply = $replyCommentAction->execute($comment, $post, $request->commentPayload());

        if($post->user_id != Auth::id()) {
            $newNotificationAction->execute($reply, NotificationType::COMMENT, $post->user, user());

            $post->user->notify((new CommentMailNotification($reply, $post->user))->locale($post->user->locale));
        }

          return $this->respondWithSuccess(

              new CommentResource($reply)
          );
    }
}
