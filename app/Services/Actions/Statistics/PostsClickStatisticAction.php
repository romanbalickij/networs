<?php


namespace App\Services\Actions\Statistics;


use App\Enums\HistoryType;
use App\Models\History;
use App\Services\Actions\GenerateGraphDateAction;
use Illuminate\Support\Facades\Auth;

class PostsClickStatisticAction
{
    public function handler($from , $to) {

        $post_clicks = History::query()
            ->generateGraphs($from, $to)
            ->type(HistoryType::POST_CLICK_HISTORY)
            ->to(Auth::id())
            ->get()
            ->keyBy('date');

        return app(GenerateGraphDateAction::class)->handler($from, $to, $post_clicks);
    }
}
