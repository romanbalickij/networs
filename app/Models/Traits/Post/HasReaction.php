<?php

namespace App\Models\Traits\Post;

use App\Enums\ReactionType;
use App\Models\Reaction;

trait HasReaction
{

    public function reactions() {

        return $this
            ->hasMany(Reaction::class, 'entity_id')
            ->where('entity_type', ReactionType::MODEL_POST);
    }

    public function addReaction($reaction) {

        $this->increment('reaction_count', 1);

        return $this
            ->reactions()
            ->create($reaction);
    }

    public function deleteReactions() :void {

        $this->reactions()->delete();
    }

    public function countReactionFire() {

        return $this->reactions->reduce(
            fn ($acc, $item) => $item->type == ReactionType::TYPE_FIRE ? ++$acc : $acc
        );
    }

    public function countReactionHeart() {

        return $this->reactions->reduce(
            fn ($acc, $item) => $item->type == ReactionType::TYPE_HEART ? ++$acc : $acc
        );
    }

    public function isHasReactionFor(?int$user, $type):bool {

        return $this
            ->reactions
            ->where('user_id', $user)
            ->where('type', $type)
            ->count();
    }


}
