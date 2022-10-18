<?php


namespace App\Services\Actions;


use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BlurImageAction
{
    //php artisan queue:listen
    public function handler($entity, $fileUrl, $folder){

        $url = env('FILE_DISK') == 'public' ? public_path($fileUrl) : fileUrl($fileUrl);

        $image = \Intervention\Image\Facades\Image::make($url);
        $width = $image->getWidth();
       // $height = $image->getHeight();

        if($width > config('image.allowed_width')) {

            $image->resize(config('image.allowed_width'), null);
        }

        $image->blur(config('image.blur'));
        $image->blur(41);

        $blurName = Str::random(30). '_blur.png';

        $blurPath = $folder ."/".$blurName;

        Storage::disk(config('app.use_file_disk'))->put($blurPath, $image->stream(), config('app.use_file_disk'));

        $entity->update([
            'blur' => $blurPath
        ]);
    }
}
