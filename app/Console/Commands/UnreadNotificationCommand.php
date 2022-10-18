<?php

namespace App\Console\Commands;

use App\Enums\NotificationType;
use App\Models\Notification;
use App\Notifications\SendUnreadNotification;
use Carbon\Carbon;
use Illuminate\Console\Command;

class UnreadNotificationCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notification:unread';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Once an hour the following is checked. If a notification is unread within 3 hours, the user is notified with a special email about the notification. Relevant for the given notification data is passed to the email.';

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
       Notification::query()
            ->unread()
            ->where('created_at', '>=', Carbon::now()->subHours(3)->toDateTimeString())
            ->chunkById(50, function($notifications) {

                foreach ($notifications as $notification) {

               //     $notification->user->notify(new SendUnreadNotification());
                }
            });
    }
}
