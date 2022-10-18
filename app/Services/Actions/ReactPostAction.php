<?php

namespace App\Services\Actions;

use App\Enums\InteractionType;
use App\Models\Post;
use App\Models\Reaction;
use Illuminate\Support\Facades\Auth;

class ReactPostAction
{

    public function execute(Post $post, array $reaction) {

        $this->setReactions($post, $reaction);
    }

    protected function setReactions($post, $reaction) {

        if($post->isHasReactionFor(Auth::id(), $reaction['type'])) {
           return;
        }
        $post->addReaction($reaction);

        $post->pushToInteractions(InteractionType::TYPE_REACTION, user());
    }
}
