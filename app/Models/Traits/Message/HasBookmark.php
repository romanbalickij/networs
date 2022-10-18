<?php

namespace App\Models\Traits\Message;

use App\Enums\BookmarkType;
use App\Models\Bookmark;
use Illuminate\Support\Facades\Auth;

trait HasBookmark
{

    public function bookmarks() {

        return $this
            ->hasMany(Bookmark::class,'entity_id')
            ->where('entity_type', BookmarkType::MODEL_MESSAGE);
    }

    public function isBookmarked() :bool {

        return $this->bookmarks
            ->isBookmarkedFor(Auth::id());
    }

    public function deleteBookmarks():void {

        $this->bookmarks()->delete();
    }
}
