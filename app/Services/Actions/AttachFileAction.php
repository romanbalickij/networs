<?php


namespace App\Services\Actions;


use App\Enums\FileType;
use App\Models\File;

class AttachFileAction
{

    public function execute(int $modelID, ?array $fileIds, $type = FileType::MODEL_POST) :void {

        $files = File::find($fileIds);

        if(!$files->count()) {
            return;
        }

        $files->attach($modelID, $type);
    }
}
