<?php

namespace App\Console\Commands;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Console\Command;

class SetClientNotificationCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'seed:notification';

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
        //todo delete  use only from test seed
        Notification::where('client_id', 0)->get()->each(function ($notification) {

            $notification->update([
               'client_id' => User::whereNotIn('id', [$notification->user_id])->inRandomOrder()->first()->id
            ]);

        });
    }
}
