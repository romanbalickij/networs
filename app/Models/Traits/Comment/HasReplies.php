<?php

namespace App\Models\Traits\Comment;

use App\Models\Comment;


trait HasReplies
{

    public function replies()
    {
        return $this->hasMany(Comment::class, 'responds_to_id')->with('replies.user');
    }

    public function addReplies($content) {

        return $this->replies()->create($content);
    }
}
