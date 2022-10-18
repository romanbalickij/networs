<?php


namespace App\Services\Actions;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TrackFnsAction
{

    public function handler(array $statuses, string|null|int $external_tracker_id = null, string|int|null $sum = 0) {


        if(!$external_tracker_id){
            return;
        }

        $pathUrl = env('TRACK_URL');

        $url  = "$pathUrl?cnv_id=$external_tracker_id";

        if($sum) {
            $url .= "&payout=$sum";
        }

        foreach ($statuses as $index => $status) {

              $url .= $index == 0 ? "&cnv_status=$status" :  "&cnv_status_" . ++$index . "=" .$status;
        }


       // dd($url);
        $response = Http::get($url);

        Log::info("TrackFnsAction :". $response->successful());
    }
}
