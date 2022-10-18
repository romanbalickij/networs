<?php

namespace App\Services\Actions;

use App\Models\Comment;
use App\Models\History;
use App\Models\Post;
use App\Models\PostClickthroughHistory;
use App\Models\PostInterestHistory;
use App\Models\PostShowHistory;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class PostStatisticAction
{

    public function handler(Post $post, $dateFrom, $dateTo) {

        $to     = $dateTo ? Carbon::parse($dateTo) : Carbon::now()->addDays(1);
        $from   = $dateFrom ? Carbon::parse($dateFrom) : Carbon::now()->subDays($to->dayOfYear - 2);


        $postInterest = $this->postInterest($post, $from, $to);
        $postClicks   = $this->postClicks($post, $from, $to);
        $postShow     = $this->postShow($post, $from, $to);
        $comments     = $this->postComment($post, $from, $to);
        $earnings     = $this->postEarnings($post, $from, $to);

        return (new Collection([
            'interests'      => app(GenerateGraphDateAction::class)->handler($from, $to, $postInterest),
            'clickthroughs'  => app(GenerateGraphDateAction::class)->handler($from, $to, $postClicks),
            'shows'          => app(GenerateGraphDateAction::class)->handler($from, $to, $postShow),
            'comments'       => app(GenerateGraphDateAction::class)->handler($from, $to, $comments),
            'earned'         => app(GenerateGraphDateAction::class)->handler($from, $to, $earnings),

            'ctr'            => $post->clickthroughs > 0 ? (float) number_format(($post->clickthroughs / $post->shows) * 100, 2) : 0
        ]));
    }

    protected function postInterest($post, $from, $to) {

        return PostInterestHistory::query()
            ->generateGraphs($from, $to)
            ->to($post)
            ->get()
            ->keyBy('date');
    }

    protected function postClicks($post, $from, $to) {

        return PostClickthroughHistory::query()
            ->generateGraphs($from, $to)
            ->to($post)
            ->get()
            ->keyBy('date');
    }

    protected function postShow($post, $from, $to) {

        return PostShowHistory::query()
            ->generateGraphs($from, $to)
            ->to($post)
            ->get()
            ->keyBy('date');
    }

    protected function postComment($post, $from, $to) {

        return Comment::query()
            ->generateGraphs($from, $to)
            ->to($post)
            ->get()
            ->keyBy('date');
    }

    protected function postEarnings($post, $from, $to) {

        return History::query()
            ->earningGraphs($from, $to)
            ->type(Post::class)
            ->typeId($post->id)
            ->get()
            ->keyBy('date');
    }

}
