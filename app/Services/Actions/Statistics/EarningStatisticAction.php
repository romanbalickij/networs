<?php


namespace App\Services\Actions\Statistics;

use App\Models\History;
use App\Services\Actions\GenerateGraphDateAction;
use Illuminate\Support\Facades\Auth;

class EarningStatisticAction
{
    public function handler($from , $to) {

        $earnings = History::query()
            ->earningGraphs($from, $to)
            ->to(Auth::id())
            ->get()
            ->keyBy('date');

        return app(GenerateGraphDateAction::class)->handler($from, $to, $earnings);
    }
}
