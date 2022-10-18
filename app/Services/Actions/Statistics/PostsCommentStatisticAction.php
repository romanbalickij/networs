<?php


namespace App\Services\Actions\Statistics;

use App\Enums\HistoryType;
use App\Models\History;
use App\Services\Actions\GenerateGraphDateAction;
use Illuminate\Support\Facades\Auth;

class PostsCommentStatisticAction
{
    public function handler($from , $to) {

        $post_comments = History::query()
            ->generateGraphs($from, $to)
            ->type(HistoryType::COMMENT)
            ->to(Auth::id())
            ->get()
            ->keyBy('date');

        return app(GenerateGraphDateAction::class)->handler($from, $to, $post_comments);
    }
}
