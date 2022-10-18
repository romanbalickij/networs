<?php


namespace App\Services\Actions\Statistics;


use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class GraphGenerateAction
{


    public function execute() {

        $to   = Carbon::now()->addDays(1);
        $from = Carbon::now()->subDays($to->dayOfYear - 2);

        $subscription   = app(SubscriptionStatisticAction::class)->handler($from, $to);
        $donation       = app(DonationStatisticAction::class)->handler($from, $to);
        $posts_shows    = app(PostsShowsStatisticAction::class)->handler($from, $to);
        $post_interests = app(PostsInterestsStatisticAction::class)->handler($from, $to);
        $post_clicks    = app(PostsClickStatisticAction::class)->handler($from, $to);
        $post_comments  = app(PostsCommentStatisticAction::class)->handler($from, $to);
        $messages       = app(MessageStatisticAction::class)->handler($from, $to);
        $earnings       = app(EarningStatisticAction::class)->handler($from, $to);

        $graphs = User::query()
            ->interactionStatistics()
            ->find(Auth::id());

        return [
          'subscriptions'  => $subscription,
          'donations'      => $donation,
          'posts_shows'    => $posts_shows,
          'post_interests' => $post_interests,
          'post_clicks'    => $post_clicks,
          'post_comments'  => $post_comments,
          'messages'       => $messages,
          'earnings'       => $earnings,

          'total_subscriptions'   => $graphs->total_subscriptions,
          'total_donations'       => $graphs->total_donations,
          'post_earnings'         => $graphs->post_earnings,
          'message_earnings'      => $graphs->message_earnings,
          'subscription_earnings' => $graphs->subscription_earnings,
          'donation_earnings'     => $graphs->donation_earnings,
          'referrals_earnings'    => $graphs->referrals_earnings,
        ];
    }
}
