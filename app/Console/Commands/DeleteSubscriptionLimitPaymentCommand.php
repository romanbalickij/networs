<?php

namespace App\Console\Commands;

use App\Models\Plan;
use App\Models\Subscription;
use App\Notifications\SubscriptionAutoProlongedCancelMailNotification;
use App\Notifications\SubscriptionAutoProlongedMailNotification;
use Illuminate\Console\Command;

class DeleteSubscriptionLimitPaymentCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'delete:subscription';

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
        Subscription::where('attempt_pay', '>=', 3)
            ->get()
            ->each(function($sub) {
                $sub->unsubscribe();
                $sub->user->notify(
                    (new SubscriptionAutoProlongedCancelMailNotification($sub))->locale($sub->user->locale)
                );
            });
    }
}
