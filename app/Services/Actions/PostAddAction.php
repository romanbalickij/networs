<?php

namespace App\Services\Actions;

use App\Models\Post;
use App\Services\DataTransferObjects\PostDto;

class PostAddAction
{


    public function execute($postDto) {
        //TODO  costile
        $postDto['is_ppv'] = $postDto['is_ppv'] == 'true' ? 1 : 0;
        $postDto['visible_until'] = $postDto['visible_until'] == 'null' ? null : $postDto['visible_until'];

        return $this->add($postDto);
    }

    protected function add($post) {

        return Post::create(collect($post)->merge([
            'user_id' => user()->id

        ])->toArray());
    }
}
