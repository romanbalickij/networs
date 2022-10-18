<?php


namespace App\Services\Actions\Statistics;


use App\Enums\HistoryType;
use App\Models\History;
use App\Services\Actions\GenerateGraphDateAction;
use Illuminate\Support\Facades\Auth;

class PostsShowsStatisticAction
{
    public function handler($from , $to) {

        $posts_shows = History::query()
            ->generateGraphs($from, $to)
            ->type(HistoryType::POST_SHOW_HISTORY)
            ->to(Auth::id())
            ->get()
            ->keyBy('date');

        return app(GenerateGraphDateAction::class)->handler($from, $to, $posts_shows);
    }
}
