<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class CloudflareCleaningVideoOldStatusPending extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cleaning:video';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'cleaning garbage with cloudflare';

    protected $cloudflareAccount;
    protected $cloudflareEmail;
    protected $cloudflareAuthToken;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->cloudflareAccount   = config('services.cloudflare.account');
        $this->cloudflareEmail     = config('services.cloudflare.email');
        $this->cloudflareAuthToken = config('services.cloudflare.auth_token');
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $client = new \GuzzleHttp\Client();

        $date = Carbon::now()->subDays(1)->format('Y-m-d\TH:i:s.u') .'Z';

        $response = $client->request('GET', "https://api.cloudflare.com/client/v4/accounts/{$this->cloudflareAccount}/stream?status=pendingupload&before=$date", [
            'headers' => [
                'X-Auth-Email' => "{$this->cloudflareEmail}",
                'X-Auth-Key' => "{$this->cloudflareAuthToken}"
            ],
        ]);

        $videos = collect(json_decode($response->getBody()->getContents()))->flatten();


        foreach ($videos as $video) {

            $this->clearVideo($client, $video->uid ?? false);
        }


    }

    protected function clearVideo($client, $id) {

        if(!$id) {return;}

        $response = $client->request('DELETE', "https://api.cloudflare.com/client/v4/accounts/{$this->cloudflareAccount}/stream/$id", [
            'headers' => [
                'X-Auth-Email' => "{$this->cloudflareEmail}",
                'X-Auth-Key' => "{$this->cloudflareAuthToken}"
            ],
        ]);

        Log::info("Cron work CloudflareCleaningVideoOldStatusPending delete !");

    }
}
