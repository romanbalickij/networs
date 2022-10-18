<?php


namespace App\Services\Actions;


use App\Exceptions\FileFailedException;
use App\Services\FileAdderSteam;
use Illuminate\Support\Facades\DB;

class SaveSteamAttachmentAction
{
    public function addFile($main, $blur, $poster)  {

      return $this->add($main, $blur, $poster);
    }

    public function addURl($mainUrl, $typeFIle, $blurUrl, $posterUrl, $otherFile) {

        try {
            DB::beginTransaction();

            $entity = app(FileAdderSteam::class)
                ->setFileURL($mainUrl, $typeFIle, $blurUrl, $posterUrl, $otherFile);

            DB::commit();

            return $entity;

        }catch (\Exception $e) {

            DB::rollback();

            throw new FileFailedException($e->getMessage());
        }
    }

    protected function add($main, $blur, $poster) {

        try {
            DB::beginTransaction();

            $entity = app(FileAdderSteam::class)
                ->setFile($main, $blur, $poster);

            DB::commit();

            return $entity;

        }catch (\Exception $e) {

            DB::rollback();

            throw new FileFailedException($e->getMessage());
        }
    }
}
