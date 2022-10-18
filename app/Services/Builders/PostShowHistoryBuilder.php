<?php

namespace App\Services\Builders;

use App\Models\Post;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class PostShowHistoryBuilder extends Builder
{

    public function generateGraphs($from, $to) {

        return $this->select([
            DB::raw('DATE(created_at) AS date'),
            DB::raw('COUNT(id) AS count'),

        ])->groupDate($from, $to);
    }

    public function groupDate($from, $to) {

        return $this
            ->whereBetween('created_at', [$from->toDateString(), $to->toDateString()])
            ->groupBy('date')
            ->orderBy('date', 'ASC');
    }

    public function to(Post $post) {

        return $this->where('post_id', $post->id);
    }

}
