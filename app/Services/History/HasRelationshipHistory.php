<?php


namespace App\Services\History;


use App\Models\History;

trait HasRelationshipHistory
{

    public function history() {

        return $this->morphMany(History::class,'historyable');
    }
}
