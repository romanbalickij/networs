<?php

namespace App\Services\Actions;

use App\Models\Post;
use App\Services\DataTransferObjects\PostDto;

class PostUpdateAction
{

    public function execute(PostDto $postDto, Post $post) {

       return tap($post)->update(
            collect($postDto)
               ->only('access', 'text', 'visible_until')
               ->toArray()
       );
    }
}
