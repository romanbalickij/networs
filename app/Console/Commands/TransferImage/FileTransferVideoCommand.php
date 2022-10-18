<?php

namespace App\Console\Commands\TransferImage;

use App\Models\File;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class FileTransferVideoCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'move:video';

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
        File::query()
            ->where('type', 'video')
            ->chunkById(50, function($files) {

                foreach ($files as $file) {


                    if(!Str::contains($file->url, 'https') and $file->url) {

                        $this->move($file);
                    }

                }
            });
    }

    protected function move($file) {

        $url = fileUrl($file->url);

        $post = [
            "url" => $url,
            'meta' => [
                'name' => $file->name
            ]
        ];

        $data = json_encode($post);

        $client = new \GuzzleHttp\Client();

        $cloudflareAccount   = config('services.cloudflare.account');
        $cloudflareEmail     = config('services.cloudflare.email');
        $cloudflareAuthToken = config('services.cloudflare.auth_token');

        try{
            DB::beginTransaction();


            $response = $client->request('POST', "https://api.cloudflare.com/client/v4/accounts/$cloudflareAccount/stream/copy", [
                'headers' => [
                    'X-Auth-Email' => "{$cloudflareEmail}",
                    'X-Auth-Key' => "{$cloudflareAuthToken}",
                    "Content-Type" => "application/json"
                ],

                'body' => $data,
            ]);


            $data = json_decode($response->getBody()->getContents());

            $video = $data->result->playback->hls;


            if($video) {

                $file->update(['url' => $video]);
            }


            DB::commit();
            dump($file->id);
        }catch (\Exception $exception) {

            DB::rollback();
        }

    }
}
