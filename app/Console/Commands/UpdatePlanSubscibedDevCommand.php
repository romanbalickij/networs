<?php

namespace App\Console\Commands;

use App\Models\Subscription;
use App\Models\User;
use Illuminate\Console\Command;

class UpdatePlanSubscibedDevCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'subscription:plan';

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
            ->chunkById(100, function($subscriptions) {

                foreach ($subscriptions as $subscription) {

                    $user = User::find($subscription->creator_id);

                    $plans = $user->plans->pluck('id');

                    $id = $plans->random();

                    $subscription->update([
                       'plan_id' => $id
                    ]);
                }

            });


    }
}
