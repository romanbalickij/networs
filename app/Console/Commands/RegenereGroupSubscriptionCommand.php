<?php

namespace App\Console\Commands;

use App\Models\Post;
use App\Models\User;
use Illuminate\Console\Command;

class RegenereGroupSubscriptionCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:group';

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

        User::query()
            ->chunkById(100, function ($users) {

                $users->each(function ($user) {


                    $user->subscribers->each(function($sub) use ($user) {

                        $array = array_merge($user->subscriberGroups->pluck('id')->toArray(), [null]);

                        $group = $array[array_rand($array)];

                        $sub->update(['subscriber_group_id' => $group]);
                                dump($group);
                    });


                });
            });
    }
}
