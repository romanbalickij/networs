<?php


namespace App\Mixins;


use ZipArchive;

class ArchivedZipMacros
{

    public function archivedZip() {

        return function ($path, ...$addFile){

            $zip = new ZipArchive();

            $zip->open($path,\ZipArchive::CREATE | \ZipArchive::OVERWRITE | ZipArchive::FL_NOCASE);

            collect($addFile)
                ->flatten()
                ->each(function ($file) use(&$zip) {

                $zip->addFile($file);
            });

            $zip->close();
        };
    }
}
