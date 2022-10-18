<?php


namespace App\Services\Actions;


use App\Models\Post;

class PostCountMediaFileAction
{


    public function handler(Post $post) {

      //  $count = $post->media_count + (isset($files) ?  + count($files) : 0);

        $post->update([
            'media_count' => $post->media->count(),
            'video_count' => $post->videos->count(),
            'image_count' => $post->images->count(),
        ]);

    }
}
