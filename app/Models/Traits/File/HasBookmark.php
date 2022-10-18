<?php

namespace App\Models\Traits\File;

use App\Enums\BookmarkType;
use App\Models\Bookmark;
use Illuminate\Support\Facades\Auth;

trait HasBookmark
{

    public function bookmarks() {

        return $this
            ->hasMany(Bookmark::class,'entity_id')
            ->where('entity_type', BookmarkType::MODEL_ATTACHMENT);
    }

    public function deleteBookmarks():void {

        $this->bookmarks()->delete();
    }

    public function isBookmarked() :bool {

        return $this->bookmarks->where('user_id', Auth::id())->count();
    }

}
