<?php

namespace App\Console\Commands;

use App\Enums\HistoryType;
use App\Enums\TrackFnType;
use App\Exceptions\PaymentFailedException;
use App\Models\Plan;
use App\Models\Subscription;
use App\Notifications\SubscriptionAutoProlongedMailNotification;
use App\Services\Actions\TrackFnsAction;
use App\Services\Payments\PaymentHandler\Entity\SubscribeEntityPayment;
use App\Services\Payments\PaymentHandler\PaymentHandler;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class AutoProlongSubscriptionCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'subscription:prolong';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        Subscription::query()
            ->where('last_payment_date', '<=', Carbon::now()->subMonth())
            ->whereHas('user.userSettings' ,fn($query) => $query->autoProlongSubscription())
            ->orderBy('id', 'asc')
            ->chunkById(10, function($subscriptions) {

                foreach ($subscriptions as $subscription) {

                    auth()->setUser($subscription->user);

                    try {

                        (new SubscribeEntityPayment($subscription->owner)) // subscribe to user
                            ->purpose('Subscribe')
                            ->payload(['plan_id' => $subscription->plan_id])
                            ->isTransaction()
                            ->historyType(HistoryType::SUBSCRIPTION)
                            ->pay(new PaymentHandler($subscription->user->defaultPaymentMethod));


                        $subscription->update(['attempt_pay' => 0]);

                        app(TrackFnsAction::class)->handler([TrackFnType::REBILL], $subscription->user->external_tracker_id, Plan::find($subscription->plan_id)->monthly_cost);


                        Log::info("Cron (AutoProlongSubscriptionCommand) is working fine! subscribe to user".$subscription->owner->id);

                    }catch (PaymentFailedException $exception) {

                        $subscription->update(['attempt_pay' => $subscription->attempt_pay + 1]);

                        $subscription->user->notify(
                            (new SubscriptionAutoProlongedMailNotification($subscription))->locale($subscription->user->locale)
                        );


                        Log::info("Cron (AutoProlongSubscriptionCommand) error :".$exception->getMessage());
                    }
                }
            });
    }
}
