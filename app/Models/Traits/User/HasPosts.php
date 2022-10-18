<?php

namespace App\Models\Traits\User;

use App\Models\Post;

trait HasPosts
{

    public function posts() {

        return $this->hasMany(Post::class);
    }

    public function post() {

        return $this->hasOne(Post::class)->latest();
    }
}
