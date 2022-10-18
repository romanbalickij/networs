<?php


namespace App\Services\Actions\Statistics;

use App\Enums\HistoryType;
use App\Models\History;
use App\Services\Actions\GenerateGraphDateAction;
use Illuminate\Support\Facades\Auth;

class SubscriptionStatisticAction
{
    public function handler($from , $to) {

        $subscription = History::query()
            ->generateGraphs($from, $to)
            ->type(HistoryType::SUBSCRIPTION)
            ->to(Auth::id())
            ->get()
            ->keyBy('date');

        return app(GenerateGraphDateAction::class)->handler($from, $to, $subscription);
    }
}
