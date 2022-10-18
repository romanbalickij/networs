<?php


namespace App\Models\Traits\User;


use App\Models\LandingCreator;

trait HasLanding
{
    public function landings() {

        return $this->hasMany(LandingCreator::class);
    }
}
