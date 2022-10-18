<?php


namespace App\Services\Actions;


use Illuminate\Support\Facades\Log;

class ErrorHandlerAction
{

    public function handler($content) {

        if(env('APP_DEBUG')) {
             return $content;
        }

        Log::error('error :'. $content);

        return 'Something went wrong';
    }

}
