<?php


namespace App\Services\Actions;


use App\Models\Post;

class PostInterestAction
{

    public function execute(Post $post) {

        return $post->addInterest();
    }
}
