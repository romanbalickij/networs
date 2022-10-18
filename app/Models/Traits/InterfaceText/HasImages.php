<?php


namespace App\Models\Traits\InterfaceText;


use App\Models\InterfaceImage;
use App\Traits\UploadFileTrait;

trait HasImages
{
    use UploadFileTrait;

    public function images() {

        return $this->hasMany(InterfaceImage::class);
    }

    public function addImages($image) :void {
        if(!$image) {return;}

         $this->deleteImages();

        $this->images()->create([
            'url'  => $this->uploadOne($image, 'images'),
            'name' => $image->getClientOriginalName()
        ]);
    }

    public function deleteImages() :void {

        $this->images->each(function ($image) {
                $this->deleteFile($image->url);
                $image->delete();
            }
        );
    }


}
