<?php

namespace App\Services;

use App\Enums\FileType;
use App\Models\File;
use App\Traits\UploadFileTrait;
use Illuminate\Support\Str;

class FileAdderSteam
{
    use UploadFileTrait;

    const FOLDER_IMAGES = 'images';
    const FOLDER_VIDEO  = 'video';
    const FOLDER_OTHER  = 'other';

    public $subjectType;


    public function setFile($file, $blur = null, $poster = null) {

        $mime = $file->getMimeType();

        $entity = match(Str::before($mime, '/')) {
            'video' => $this->saveFile($file, self::FOLDER_VIDEO, FileType::TYPE_VIDEO, $poster, $blur),
            'image' => $this->saveFile($file, self::FOLDER_IMAGES, FileType::TYPE_IMAGE, $poster, $blur),
            default => $this->saveFile($file, self::FOLDER_OTHER, FileType::TYPE_OTHER),
        };

        return $entity;
    }

    public function setFileURL($mainUrl, $typeFIle, $blurUrl, $posterUrl, $otherFile) {

         return match($typeFIle) {
            'video' => $this->saveAddressFile($mainUrl, $typeFIle, $blurUrl, $posterUrl),
            'image' => $this->saveAddressFile($mainUrl, $typeFIle, $blurUrl, $posterUrl),
            default => $this->saveFile($otherFile, self::FOLDER_OTHER, FileType::TYPE_OTHER),
        };
    }


    public function setSubjectType($subjectType): self
    {
        $this->subjectType = $subjectType;

        return $this;
    }

//    php artisan queue:listen
    protected function saveFile($file, $folder, $type, $posterFile = null, $blurFile = null)  {

        $mainPath = $this->uploadSteamFile($file, $folder);

        $posterPath = $posterFile ? $this->uploadSteamFile($posterFile, self::FOLDER_IMAGES) : NULL;

        $blurPath = $blurFile ? $this->uploadSteamFile($blurFile, self::FOLDER_IMAGES) : NULL;


        $entity = File::create([
            'type'        => $type,
            'name'        => $file->getClientOriginalName(),
            'mime_type'   => $file->getMimeType(),
            'url'         => $mainPath,
            'poster'      => $posterPath,
            'blur'        => $blurPath,
        ]);

        return $entity;
    }

    protected function saveAddressFile($mainUrl, $typeFIle, $blurUrl, $posterUrl) {

        $entity = File::create([
            'type'        => $typeFIle,
            'url'         => $mainUrl,
            'poster'      => $posterUrl,
            'blur'        => $blurUrl,
            'mime_type'   => $typeFIle,
        ]);

        return $entity;
    }

}
