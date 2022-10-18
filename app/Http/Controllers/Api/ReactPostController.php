<?php

namespace App\Http\Controllers\Api;

use App\Enums\NotificationType;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Post\ReactPostRequest;
use App\Models\Post;
use App\Notifications\ReactionMailNotification;
use App\Services\Actions\NewNotificationAction;
use App\Services\Actions\ReactPostAction;

class ReactPostController extends BaseController
{

    public function __invoke(Post $post, ReactPostRequest $request, ReactPostAction $reactPostAction, NewNotificationAction $newNotificationAction) {

        $reactPostAction->execute($post, $request->reactPostPayload());

        $notification = $newNotificationAction->execute($post, NotificationType::REACTION_POST, $post->user, user());

        $post->user->notify(
            (new ReactionMailNotification($notification))->locale($post->user->locale)
        );

        return $this->respondOk('The reaction Post  added successfully');
    }
}
