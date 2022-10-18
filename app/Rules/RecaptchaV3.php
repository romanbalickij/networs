<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\App;

class RecaptchaV3 implements Rule
{

    public function __construct()
    {
        //
    }


    public function passes($attribute, $value)
    {
//        if(!App::environment('production')) {
//            return true;
//        }

        $url = 'https://www.google.com/recaptcha/api/siteverify';
        $data = ['secret' => config('services.recaptcha.secret_key'), 'response' => $value];
        $options = ['http' => [
            'header' => "Content-type: application/x-www-form-urlencoded\r\n",
            'method' => 'POST',
            'content' => http_build_query($data)
        ]];
        $context = stream_context_create($options);
        $response = file_get_contents($url, false, $context);
        $response_keys = json_decode($response, true);

        if (!$response_keys['success']) {
            $this->error_codes = $response_keys['error-codes'];
        }
        return $response_keys['success'];
    }


    public function message()
    {
        $msg = 'Recaptcha failed ()_()';
        if (!empty($this->error_codes)) {
            $msg = implode(', ', $this->error_codes);
        }
        return $msg;
    }
}
