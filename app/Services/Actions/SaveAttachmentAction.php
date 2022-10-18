<?php

namespace App\Services\Actions;

use App\Services\FileAdder;

class SaveAttachmentAction
{

    public function run($files, $model, $type) :void {

        $this->add($files, $model, $type);
    }

    protected function add($files, $model, $type) {

        if(empty($files) ) {
            return;
        }

        collect($files)->each(function ($file) use ($model, $type) {

            app(FileAdder::class)
                ->setSubject($model)
                ->setSubjectType($type)
                ->setFile($file);
        });
    }
}
