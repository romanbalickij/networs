<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\BaseController;
use App\Http\Resources\Bookmark\BookmarkCollection;
use App\Models\Bookmark;

class BookmarkController extends BaseController
{
    public function index() {

        $bookmark = Bookmark::query()
            ->orderBy('id', 'desc')
            ->with([
                'file',
                'file.entity.payments',
                'post.allComments:id,post_id',
                'post.media.entity.payments',
                'post.others.entity.payments',
                'message.media.entity.payments',
                'message.others.entity.payments',
                'user'
            ])
            ->where('user_id', user()->id)
            ->cursorPaginate($this->perPage());

        return $this->respondWithSuccess(

            new BookmarkCollection($bookmark)
        );
    }

    public function destroy(Bookmark $bookmark) {

        $bookmark->delete();

        return $this->respondOk('The Bookmark deleted successfully');
    }
}
