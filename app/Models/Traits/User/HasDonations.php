<?php

namespace App\Models\Traits\User;

use App\Models\Donation;

trait HasDonations
{

    public function donations() {

        return $this->hasMany(Donation::class);
    }
}
