<?php


namespace App\Models\Traits\User;


use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

trait HasBackground
{

    public function generateBackground($file) {

        if(!$file) {
            return;
        }

        $this->deleteBackground();

        $this->forceFill([
            'background' => $this->uploadOne($file, 'images')
        ])->save();
    }

    public function deleteBackground()
    {
        if (!$this->background) {
            return;
        }
        $this->deleteFile($this->background);

        $this->forceFill([
            'background' => null,
        ])->save();
    }

    public function getBackgroundAttribute($value)
    {
        return fileUrl($value);
    }
}
