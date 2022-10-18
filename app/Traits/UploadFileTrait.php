<?php

namespace App\Traits;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Psr\Http\Message\StreamInterface;
use Illuminate\Http\File;

trait UploadFileTrait
{
    public function uploadOne(StreamInterface|UploadedFile $uploadedFile, $folder = null, $disk = 'public', $filename = null)
    {
        $disk = config('app.use_file_disk');

        $name = !is_null($filename) ? $filename : Str::random(25);

        return $uploadedFile->storeAs($folder, $name.'.'.$uploadedFile->getClientOriginalExtension(), $disk);
    }

    public function uploadSteamFile($file, $folder, $originalExtension = null) {

       // $name = Str::random(30). '.' .$originalExtension;

       // $path = $folder ."/".$name;

       // Storage::disk(config('app.use_file_disk'))->put($path, $file, config('app.use_file_disk'));

         $path = Storage::disk(config('app.use_file_disk'))->putFile($folder,  $file);

         return $path;
    }

    public function deleteFile($file) {
        if($file != null){
         //   Storage::delete($file);
            if(Storage::disk(config('app.use_file_disk'))->exists($file)) {
                Storage::disk(config('app.use_file_disk'))->delete($file);
            }
        }
    }

}
