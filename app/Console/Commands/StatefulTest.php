<?php

namespace App\Console\Commands;

use App\Stateful\Controllers\EventController;
use Illuminate\Console\Command;

class StatefulTest extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'stateful:test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run a test request on Websocket Server';


    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        EventController::trigger(1, 'notification', ['content' => 45]);

        return 0;
    }
}
