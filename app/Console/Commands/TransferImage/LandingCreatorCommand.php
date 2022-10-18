<?php

namespace App\Console\Commands\TransferImage;

use App\Models\InterfaceImage;
use App\Models\LandingCreator;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class LandingCreatorCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'move:land-creator';

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

    public function handle()
    {
        LandingCreator::query()
            ->chunkById(50, function($images) {

                foreach ($images as $image) {


                    if(!Str::contains($image->img, 'https://imagedelivery.net') and $image->img) {

                        $this->move($image);
                    }

                }
            });
    }

    protected function move($file) {

        $url = fileUrl($file->img);

        $client = new \GuzzleHttp\Client();


        $cloudflareAccount   = config('services.cloudflare.account');
        $cloudflareEmail     = config('services.cloudflare.email');
        $cloudflareAuthToken = config('services.cloudflare.auth_token');

        try{
            DB::beginTransaction();

            $response = $client->request('POST', "https://api.cloudflare.com/client/v4/accounts/$cloudflareAccount/images/v1", [
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


            $img = collect($data->result->variants)->filter(fn($url) => Str::contains($url, 'public'))->first();


            if(isset($img)) {

                $file->update(['img' => $img]);
            }

            DB::commit();

            dump($file->id);
        }catch (\Exception $exception) {

            DB::rollback();
        }

    }
}
