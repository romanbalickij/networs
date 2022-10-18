<?php


namespace App\Services\Actions;


use App\Models\Post;

class PostShowAction
{

    public function execute(Post $post) {

        return $post->addShowHistories();
    }
}
