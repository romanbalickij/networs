<?php

namespace App\Models\Traits\Post;

use App\Enums\FileType;
use App\Models\File;
use App\Models\Traits\General\RelationshipFile;
use App\Services\Actions\SaveAttachmentAction;
use App\Traits\UploadFileTrait;


trait HasFiles
{
    use RelationshipFile;
    use UploadFileTrait;

    protected function relationshipModelAs() {

        return FileType::MODEL_POST;
    }

    public function addAttachments($files) {

        return app(SaveAttachmentAction::class)->run($files, $this, FileType::MODEL_POST);
    }

    public function unlockFor($id){

        return (bool) optional($this->media->first())->isUnlockedFor($id);
    }

}
