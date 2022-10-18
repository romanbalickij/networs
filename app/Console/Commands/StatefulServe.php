<?php


namespace App\Console\Commands;


use App\Models\User;
use BeyondCode\LaravelWebSockets\Console\StartWebSocketServer;
use App\Stateful\RatchetAdapter;
use Illuminate\Support\Facades\DB;
use OpenApi\LinkExample\UsersController;

class StatefulServe extends StartWebSocketServer
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'stateful:serve {--host=0.0.0.0} {--port=6001} {--debug : Forces the loggers to be enabled and thereby overriding the app.debug config setting } ';


    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command for starting the websocket server';


    public function handle()
    {
        /** @noinspection SqlWithoutWhere */
        DB::update('update users set is_online = false');

        $this
//            ->configureStatisticsLogger()
            ->configureHttpLogger()
            ->configureMessageLogger()
            ->configureConnectionLogger()
            ->configureRestartTimer()
            ->registerCustomRoutes()
            ->startWebSocketServer();
    }

    public function configureRestartTimer()
    {
        $this->lastRestart = $this->getLastRestart();

        $this->loop->addPeriodicTimer(3600, function () {
            if ($this->getLastRestart() !== $this->lastRestart) {
                $this->loop->stop();
            }
        });

        return $this;
    }
}
