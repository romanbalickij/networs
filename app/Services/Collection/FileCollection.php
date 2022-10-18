<?php


namespace App\Services\Collection;

use Illuminate\Database\Eloquent\Collection;

class FileCollection extends Collection
{

    public function attach($id, $type) {

         $this->each(function ($file) use ($id, $type) {

             $file->update(['entity_id' => $id, 'entity_type' => $type]);
         });
    }
}
