<?php


namespace App\Services\Actions;


use App\Traits\UploadFileTrait;
use Intervention\Image\Facades\Image;

class ResizeImageAction
{
    use UploadFileTrait;

    public function handler($file, $folder) {

        $image = Image::make($file->path());

        $width = $image->getWidth();

        if($width > config('image.allowed_width')) {

            $image->resize(config('image.allowed_width'), null);
        }

        return $this->uploadSteamFile($image->stream(), $folder, $file->getClientOriginalExtension());
    }
}
