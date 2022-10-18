<?php

namespace App\Models\Traits\User;

use App\Enums\BookmarkType;
use App\Enums\InteractionType;
use App\Models\Bookmark;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

trait HasBookmark
{

    public function bookmarked() {

        return $this
            ->hasMany(Bookmark::class,'entity_id')
            ->where('entity_type', BookmarkType::MODEL_USER);
    }

    public function bookmarks() {

        return $this->hasMany(Bookmark::class);
    }

    public function isBookmarked() :bool {

        return $this->bookmarked->isBookmarkedFor(Auth::id());
    }


    public function hasBookmarkedFor(Model $model, string $type) :bool {

        return $this->bookmarks()
            ->where(fn ($query) => $query->typeBookmark($type, $model))
            ->count();
    }

    public function toggleBookmarked(Model $model, $type = 'user') {

        return $this->hasBookmarkedFor($model, $type)
                ? $this->removeBookmarked($model, $type)
                : $this->addBookmarked($model, $type);
    }

    public function addBookmarked(Model $model, string $type) {

        return $this
            ->bookmarks()
            ->create(['entity_type' => $type, 'entity_id' => $model->id]);
    }

    public function removeBookmarked(Model $model, string $type) {

        return $this->bookmarks()
            ->where(fn ($query) => $query->typeBookmark($type, $model))
            ->first()
            ->remove();
    }
}
