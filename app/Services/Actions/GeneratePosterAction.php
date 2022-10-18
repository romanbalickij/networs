<?php


namespace App\Services\Actions;


use Illuminate\Support\Str;
use ProtoneMedia\LaravelFFMpeg\Support\FFMpeg;

class GeneratePosterAction
{
    const PATH_POSTER = 'poster';

    public function handler($filePatch, $fromDisk) {

        $namePoster = $this->generate();
             //   dd($filePatch, $fromDisk);
        FFMpeg::
             fromDisk($fromDisk)
            ->open($filePatch)
            ->getFrameFromSeconds(3)
            ->export()
            ->toDisk(config('app.use_file_disk'))
            ->save($namePoster);

        return $namePoster;
    }

    public function generate() {
        $posterName     = Str::random(30). '_poster.png';

        $posterDisk     = self::PATH_POSTER;

        $fullPathPoster = "$posterDisk/$posterName";

        return $fullPathPoster;
    }
}
