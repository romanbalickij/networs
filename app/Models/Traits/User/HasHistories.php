<?php


namespace App\Models\Traits\User;


use App\Models\History;

trait HasHistories
{


    public function histories() {

        return $this->hasMany(History::class);
    }
}
