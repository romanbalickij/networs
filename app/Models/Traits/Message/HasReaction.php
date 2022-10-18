<?php

namespace App\Models\Traits\Message;

use App\Enums\ReactionType;
use App\Models\Reaction;

trait HasReaction
{

    public function reactions() {

        return $this
            ->hasMany(Reaction::class, 'entity_id')
            ->where('entity_type', ReactionType::MODEL_MESSAGE);

    }

    public function deleteReactions() :void {

        $this->reactions()->delete();
    }

    public function addReaction($reaction) {

       return $this->reactions()->create($reaction);
    }
}
