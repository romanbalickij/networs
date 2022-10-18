<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class PrerenderCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'prerender:run';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Once every 6 hours an NPM command is triggered, causing a prerender of all public pages..
     This process is taken into account in estimation by increasing the value of components requiring prerendering as shown
      is the estimate attached to this specifications.';

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
        dd(User::first());
        shell_exec('vue-cli-service serve frontend/src/main.js');
    }
}
