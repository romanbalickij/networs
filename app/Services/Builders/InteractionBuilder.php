<?php

namespace App\Services\Builders;

use App\Models\Chat;
use App\Models\Comment;
use App\Models\File;
use App\Models\Invoice;
use App\Models\Message;
use App\Models\Post;
use App\Models\Subscription;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\MorphTo;


class InteractionBuilder extends Builder
{

    public function loadInteractionable() {

       return $this->with(['interactionable' => function (MorphTo $morphTo) {
            $morphTo->constrain([
                Chat::class         => fn (Builder $query) => $query->with(['message', 'user']),
                Post::class         => fn (Builder $query) => $query->with(['allComments']),
                Message::class      => fn (Builder $query) => $query->withTrashed()->with(['bookmarks']),
                Comment::class      => fn (Builder $query) => $query->with(['user', 'replies.user']),
                Invoice::class      => fn (Builder $query) => $query->with(['user']),
                Subscription::class => fn (Builder $query) => $query->withTrashed()->with(['plan', 'owner.post.media', 'owner.post.allComments:id,post_id', 'owner.post.reactions', 'user.userSettings']),
                File::class         => fn (Builder $query) => $query->with(['entity.payments']),
            ]);
        }]);
    }
}
