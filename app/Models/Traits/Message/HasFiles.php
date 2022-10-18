<?php

namespace App\Models\Traits\Message;

use App\Enums\FileType;
use App\Models\Traits\General\RelationshipFile;
use App\Services\Actions\SaveAttachmentAction;
use App\Traits\UploadFileTrait;

trait HasFiles
{
    use RelationshipFile;
    use UploadFileTrait;


    protected function relationshipModelAs() {

        return FileType::MODEL_MESSAGE;
    }

    public function addAttachments($files) {

        return app(SaveAttachmentAction::class)->run($files, $this, FileType::MODEL_MESSAGE);
    }

    public function hasAttachments():bool {

        return $this->files()->count();
    }

    public function unlockFor($id){

        return (bool) optional($this->media->first())->isUnlockedFor($id);
    }
}
