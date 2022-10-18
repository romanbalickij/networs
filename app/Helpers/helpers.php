<?php


use App\Models\File;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;

function user() {

    return auth()->user();
}

function managed() {

   return user()->current_account ? loginManaged() : user();
}

function loginManaged() {

   return auth()->setUser(User::find(user()->current_account))->user();
}

function generateNameUser() :string {

    return Str::of('User_')->append(rand(0, 999999));
}

function isCookieContent() {

    return request()->cookie('CookieConsent') == 'yes';
}

function getJsonConfig($key) {

    if(!file_exists(storage_path("config/$key.json"))) {
        return [];
    }

    return json_decode(file_get_contents(storage_path("config/$key.json")),true);
}

function splitName ($fulName) {
    list($firstName, $lastName) = array_pad(explode(' ', trim($fulName)), 2, null);
    return  [
        'name'     => $firstName,
        'surname'  => $lastName,
    ];
}

function fileUrl($file, $urlFromCloudFare = true) {

    if(!$file) {return ;}

//    if(env('APP_ENV') == 'production' and $urlFromCloudFare) {
//        return $file;
//    }

    if( Str::contains($file, 'https')) {
        return $file;
    }

   return env('FILE_DISK') == 'public'
        ? Storage::disk('public')->url($file)
        : Storage::disk('s3')->temporaryUrl($file, now()->addDays(7));
}

function getLetterText($letters, $key) {

    return collect($letters)->keyBy('key')->get($key)['text'] ?? '<div></div>';
}

function createTemporaryLink($routeName, array $params) {

    return URL::temporarySignedRoute(

        $routeName, now()->addMinutes(config('app.signed_route_time')), $params
    );
}

function letterRenderImage($imagePath) {

    $url = fileUrl($imagePath, false);

    return fullPath($url);
}

function letterRenderAvatar($path) {

    if(Str::contains($path, 'ui-avatars.com/api')) {
        return $path;
    }

//    if(env('APP_ENV') == 'production') {
//        return $path;
//    }

    if( Str::contains($path, 'https')) {
        return $path;
    }

    return fullPath($path);
}

function fullPath($url) {

    return env('FILE_DISK') == 'public' ? env('APP_URL').$url : $url;
}


function getHardcoverPost() {

    $prod = [105808,105807,105809,105831,105832];
    $dev =  [1,3,4,5, 6,7,8,9, 10,11, 12,13,14, 15, 16,17, 18, 19, 20];

    return env('APP_ENV') == 'production' ? $prod : $dev;
}

