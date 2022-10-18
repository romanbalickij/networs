<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\BaseController;
use App\Http\Resources\Post\PostCreatorContentCollection;
use App\Http\Resources\User\UserPostCollection;
use App\Models\User;
use App\Services\Filters\PostFilter;

class UserContentCreatorController extends BaseController
{

    public function __invoke(User $author, PostFilter $postFilter)
    {

        return $this->respondWithSuccess(

            (new PostCreatorContentCollection($author
                ->posts()
                ->visibleUntil()
                ->filter($postFilter)
                ->orderBy('id', 'desc')
          //      ->allowedPrivatePostForSubscriber($author, user())
               // ->pinnedDisplayedAboveAll()
                ->with([
                    'reactions',
                    'others.entity.payments',
                    'media.entity.payments',
                    'media.entity.user.subscribers',
                    'user',
                    'bookmarks',
                    'allComments',
                    'media.bookmarks',
                    'others.bookmarks'
                ])->cursorPaginate($this->perPage())
            )
            )
        );
    }
}
