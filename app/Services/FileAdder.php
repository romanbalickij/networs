<?php

namespace App\Services;

use App\Enums\FileType;
use App\Jobs\ImageBlurJob;
use App\Services\Actions\BlurImageAction;
use App\Services\Actions\GeneratePosterAction;
use App\Services\Actions\ResizeImageAction;
use App\Traits\UploadFileTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;
use Intervention\Image\ImageManager;

#use Spatie\Image\Image;

class FileAdder
{
    use UploadFileTrait;

    const FOLDER_IMAGES = 'images';
    const FOLDER_VIDEO  = 'video';

    protected Model $subject;

    protected string $subjectType;



    public function setFile($file): self {

        $mime = $file->getMimeType();

        if(strstr($mime, "video/")){

             $this->saveVideo($file);
        }

        if(strstr($mime, "image/")){

            $this->saveImage($file);
        }

        if(!strstr($mime, "image/") and !strstr($mime, "video/")){

            $this->saveOther($file);
        }

        return $this;
    }

    public function setSubject(Model $subject): self
    {
        $this->subject = $subject;

        return $this;
    }

    public function setSubjectType($subjectType): self
    {
        $this->subjectType = $subjectType;

        return $this;
    }
//    php artisan queue:listen
    protected function saveVideo($file, $disk = 'video') : self {

        $url = $this->uploadOne($file ,$disk);

//        $video = env('APP_ENV') == 'dev'
//            ? Str::of($url)->after($disk.'/')
//            : $url;

        $to = config('app.use_file_disk');

      //  dd($video, $url, $to);
        $fullPathPoster = $this->generatePoster($url, $to);

        $entity = $this->subject->videos()->create([
            'entity_type' => $this->subjectType,
            'type'        => FileType::TYPE_VIDEO,
            'name'        => $file->getClientOriginalName(),
            'mime_type'   => $file->getMimeType(),
            'url'         => $url,
            'poster'      => $fullPathPoster,
        ]);

        dispatch(new ImageBlurJob($entity, $entity->poster, 'images'));

        return $this;
    }

    protected function saveImage($file, $folder = 'images') : self {

       $url = app(ResizeImageAction::class)->handler($file, $folder);

       $entity = $this->subject->images()->create([
            'entity_type' => $this->subjectType,
            'type'        => FileType::TYPE_IMAGE,
            'name'        => $file->getClientOriginalName(),
            'mime_type'   => $file->getMimeType(),
            'url'         => $url,
        ]);

        dispatch(new ImageBlurJob($entity, $entity->url, $folder));

        return $this;
    }

    protected function saveOther($file, $folder = 'other') : self {

        $this->subject->others()->create([
            'entity_type' => $this->subjectType,
            'type'        => FileType::TYPE_OTHER,
            'name'        => $file->getClientOriginalName(),
            'mime_type'   => $file->getMimeType(),
            'url'         => $this->uploadOne($file ,$folder)
        ]);

        return $this;
    }

    protected function generatePoster($file, string $fromDisk) {

       return app(GeneratePosterAction::class)->handler($file, $fromDisk);
    }

}
