<?php

namespace App\Models\Traits\Post;

use App\Enums\BookmarkType;
use App\Models\Bookmark;
use Illuminate\Support\Facades\Auth;

trait HasBookmark
{

    public function bookmarks() {

        return $this
            ->hasMany(Bookmark::class,'entity_id')
            ->where('entity_type', BookmarkType::MODEL_POST);
    }

    public function isBookmarked() :bool {

        return $this->bookmarks->isBookmarkedFor(Auth::id());//collection
    }

    public function hasBookmarkedFor($user) :bool {

       return $this->bookmarks->contains(function ($bookmark) use ($user) {

            return $bookmark->user_id === $user->id;
        });
    }

    public function removeBookmarked($author) {

        return $this->bookmarks()
            ->where('user_id', $author->id)->delete();
    }

    public function deleteBookmarks() {

        return $this->bookmarks()->delete();
    }
}
