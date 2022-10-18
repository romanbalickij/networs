<?php

namespace App\Console\Commands\TransferImage;

use App\Models\User;
use DeDmytro\CloudflareImages\Facades\CloudflareApi;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TestProdUploadAvatarToCloudFare extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'move:avatar';

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
        User::query()
            ->chunkById(50, function($users) {

            foreach ($users as $user) {


                if(!Str::contains($user->avatar, 'https') and $user->avatar) {

                    $this->move($user);
                }

            }
        });
    }

    protected function move($user) {

        $url = fileUrl($user->avatar);

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

            $img = collect($data->result->variants)->filter(fn($url) => Str::contains($url, 'public'))->first();

            if(isset($img)) {

                $user->update(['avatar' => $img]);
            }

            DB::commit();

            dump($user->id);

        }catch (\Exception $exception) {
            DB::rollback();
        }



        // problem from name and save to local !;
//        $info = pathinfo($url);
//        $contents2 = file_get_contents($url);
//        $file = '/tmp/' . $user->avatar;
//        file_put_contents($file, $contents2);
//
//        $response = CloudflareApi::images()->upload($file);
//
//        $image = $response->result;
//
//        dd($image);
    }
}
