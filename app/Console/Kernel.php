<?php

namespace App\Console;

use App\Console\Commands\AutoProlongSubscriptionCommand;
use App\Console\Commands\CacheClearCommand;
use App\Console\Commands\ClearFolderExportCommand;
use App\Console\Commands\CloudflareCleaningVideoOldStatusPending;
use App\Console\Commands\CountMediaPostCommand;
use App\Console\Commands\CryptoPaymentConfirmationCommand;
use App\Console\Commands\DeleteSubscriptionLimitPaymentCommand;
use App\Console\Commands\PrerenderCommand;
use App\Console\Commands\QualityAlgorithmCreatorCommand;
use App\Console\Commands\SortingQualityAlgorithmPostCommand;
use App\Console\Commands\TestCronWorkCommand;
use App\Console\Commands\UnreadMessageCommand;
use App\Console\Commands\UnreadNotificationCommand;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')->hourly();

        // Cleaning models
        $schedule->command('model:prune')->daily();

        //Checks  the payment was successful
        $schedule->command(CryptoPaymentConfirmationCommand::class)->hourly();

        //Unread messages service
        $schedule->command(UnreadMessageCommand::class)->hourly();

        //Unread notification service
     //   $schedule->command(UnreadNotificationCommand::class)->hourly();

        //pay subscription auto prolong
        $schedule->command(AutoProlongSubscriptionCommand::class)->daily();

        //remove a subscriber if the payment attempt is greater than 3
        $schedule->command(DeleteSubscriptionLimitPaymentCommand::class)->daily();

        //Once every 6 hours an NPM command is triggered, causing a prerender of all public pages.
       // $schedule->command(PrerenderCommand::class)->everySixHours();

        // The algorithm for displaying posts
        $schedule->command(SortingQualityAlgorithmPostCommand::class)->daily();

        // The algorithm quality creator posts
        $schedule->command(QualityAlgorithmCreatorCommand::class)->daily();

        // Delete cache from database
        $schedule->command(CacheClearCommand::class)->daily();

        //Test command check if cron work
        $schedule->command(TestCronWorkCommand::class)->everyTenMinutes();

        //regular cleaning of garbage from cloudflare status pendingupload
        $schedule->command(CloudflareCleaningVideoOldStatusPending::class)->daily();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
