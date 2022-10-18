<?php

namespace App\Jobs;

use App\Services\Actions\BlurImageAction;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImageBlurJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $entity;

    protected $folder;

    protected $fileUrl;

    public function __construct($entity, $fileUrl,  $folder)
    {
        $this->entity = $entity;

        $this->folder = $folder;

        $this->fileUrl = $fileUrl;
    }


    public function handle()
    {

        app(BlurImageAction::class)->handler(
                $this->entity,
                $this->fileUrl,
                $this->folder
            );
    }
}
