<?php

namespace App\Models\Traits\Post;

use App\Models\PostClickthroughHistory;
use Illuminate\Support\Facades\Auth;

trait HasClickthroughHistories
{

    public function clickthroughHistories() {

        return $this->hasMany(PostClickthroughHistory::class);
    }

    public function addClicks() {

        $this->increment('clickthroughs', 1);

        return $this->clickthroughHistories()
            ->create(['user_id' => Auth::id()]);
    }
}
