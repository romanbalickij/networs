<?php

namespace App\Models\Traits\Post;

use App\Models\PostInterestHistory;
use Illuminate\Support\Facades\Auth;

trait HasInterestHistories
{

    public function interestHistories() {

        return $this->hasMany(PostInterestHistory::class);
    }

    public function addInterest() {

        $this->increment('interested', 1);

        return $this->interestHistories()
            ->create(['user_id' => Auth::id()]);
    }
}
