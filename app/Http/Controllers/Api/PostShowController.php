<?php

namespace App\Http\Controllers\Api;


use App\Http\Controllers\BaseController;
use App\Models\Post;
use App\Services\Actions\PostShowAction;
use Illuminate\Http\Request;

class PostShowController extends BaseController
{

    public function  __invoke(Post $post, PostShowAction $postShowAction) {

        $postShowAction->execute($post);

        return $this->respondWithSuccess('Success');
    }
}
