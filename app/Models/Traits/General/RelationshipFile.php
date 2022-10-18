<?php

namespace App\Models\Traits\General;

use App\Enums\FileType;
use App\Models\File;

trait RelationshipFile
{
    public function videos() {

        return $this->hasMany(File::class, 'entity_id')
            ->where('entity_type' , $this->relationshipModelAs())
            ->where('type', FileType::TYPE_VIDEO);
    }

    public function images() {

        return $this->hasMany(File::class, 'entity_id')
            ->where('entity_type' , $this->relationshipModelAs())
            ->where('type', FileType::TYPE_IMAGE);
    }

    public function media() {

        return $this->morphMany(File::class, 'entity', 'entity_type')
            ->whereIn('type', [FileType::TYPE_IMAGE, FileType::TYPE_VIDEO]);
    }


    public function others() {

        return $this->morphMany(File::class, 'entity', 'entity_type')
            ->where('type', FileType::TYPE_OTHER);
    }

    public function files() {

        return $this->hasMany(File::class, 'entity_id')
            ->where('entity_type' , $this->relationshipModelAs());
    }

    public function deleteAttachments():void {

        $this->files->each(function ($file) {

            $this->deleteFile($file->url);
            $this->deleteFile($file->poster);
            $this->deleteFile($file->blur);
            $file->delete();
        });
    }
}
