<?php


namespace App\Services\Collection;

use Illuminate\Database\Eloquent\Collection;

class BookmarkCollection extends Collection
{

    public function isBookmarkedFor(?int $id) {

        return $this->where('user_id', $id)->count();
    }
}
