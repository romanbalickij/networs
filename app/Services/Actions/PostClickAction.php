<?php


namespace App\Services\Actions;


use App\Models\Post;

class PostClickAction
{

    public function execute(Post $post) {

        return $post->addClicks();
    }
}
