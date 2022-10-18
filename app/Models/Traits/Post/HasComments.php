<?php

namespace App\Models\Traits\Post;

use App\Models\Comment;

trait HasComments
{

    public function comments() {

        return $this
            ->hasMany(Comment::class)
            ->whereNull('responds_to_id');
    }

    public function allComments() {

        return $this->hasMany(Comment::class);
    }

    public function getComments() {

        return $this->comments;
    }

    public function addComment($comment) {

       return $this
           ->comments()
           ->create($comment);
    }

    public function commentCount() {

        return $this->allComments->count();
    }
}
