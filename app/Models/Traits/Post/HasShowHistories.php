<?php

namespace App\Models\Traits\Post;

use App\Models\PostShowHistory;
use Illuminate\Support\Facades\Auth;

trait HasShowHistories
{

    public function showHistories() {

        return $this->hasMany(PostShowHistory::class);
    }

    public function addShowHistories() {

        $this->increment('shows', 1);

        return $this->showHistories()
            ->create(['user_id' => Auth::id()]);
    }
}
