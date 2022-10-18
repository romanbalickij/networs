<?php

namespace App\Services\Builders;

use App\Models\Post;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class CommentBuilder extends Builder
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

    public function moderated() {

        return $this->where('moderated', true);
    }

    public function nonModerated() {

        return $this->where('moderated', false);
    }

    public function olderThanMonth() {

        return $this->where('created_at', '>=', Carbon::now()->subDays(30));
    }

}
