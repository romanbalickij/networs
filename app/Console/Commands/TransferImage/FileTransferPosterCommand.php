<?php

namespace App\Console\Commands\TransferImage;

use App\Models\File;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class FileTransferPosterCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'move:poster';

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


                    if(!Str::contains($file->poster, 'https://imagedelivery.net') and $file->poster) {

                        $this->move($file);
                    }

                }
            });
    }

    protected function move($file) {

        $url = fileUrl($file->poster);

        $client = new \GuzzleHttp\Client();

        $cloudflareAccount   = config('services.cloudflare.account');
        $cloudflareEmail     = config('services.cloudflare.email');
        $cloudflareAuthToken = config('services.cloudflare.auth_token');

        try{
            DB::beginTransaction();

            $response = $client->request('POST  ', "https://api.cloudflare.com/client/v4/accounts/$cloudflareAccount/images/v1", [
                'headers' => [
                    'X-Auth-Email' => "{$cloudflareEmail}",
                    'X-Auth-Key' => "{$cloudflareAuthToken}",
                ],
                'multipart' => [
                    [
                        'name' => 'url',
                        'contents' => $url
                    ]
                ]
            ]);


            $data = json_decode($response->getBody()->getContents());


            $img     = collect($data->result->variants)->filter(fn($url) => Str::contains($url, 'public'))->first();
            $blurred = collect($data->result->variants)->filter(fn($url) => Str::contains($url, 'blurred'))->first();

            if(isset($img)) {

                $file->update(['poster' => $img]);
            }

            if(isset($blurred)) {
                $file->update(['blur' => $blurred]);
            }

            DB::commit();
            dump($file->id);
        }catch (\Exception $exception) {
            DB::rollback();
        }

    }
}
