<?php

namespace App\Services\Actions;

use App\Models\Post;

class PostPinnedAction
{

    public function execute(Post $post) :void {

        $post->pinned();

         user()
            ->posts()
            ->where('id' , '!=', $post->id)
            ->chunkById(200, fn ($post) => $post->each(fn($p) => $p->unpinned()));
    }
}
