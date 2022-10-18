<?php

namespace App\Models\Traits\User;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

trait HasProfilePhoto
{

    public function getProfilePhotoUrlAttribute()
    {
        return $this->avatar
            ? fileUrl($this->avatar)
            : $this->defaultProfilePhotoUrl();
    }

    protected function defaultProfilePhotoUrl()
    {
        $name = $this->name ?? Str::after($this->nickname, '@');

        return 'https://ui-avatars.com/api/?name='.urlencode($name).'&color=7F9CF5&background=EBF4FF';
    }

    protected function profilePhotoDisk()
    {
        return config('app.use_file_disk');
    }

    public function generateProfilePhoto($photo) {

        if(!$photo) {
            return;
        }

        $this->deleteProfilePhoto();

        $this->forceFill([
            'avatar' => $this->uploadOne($photo, 'images')
        ])->save();
    }

    public function deleteProfilePhoto()
    {
        if (!$this->avatar) {
            return;
        }
        $this->deleteFile($this->avatar);

        $this->forceFill([
            'avatar' => null,
        ])->save();
    }

}
