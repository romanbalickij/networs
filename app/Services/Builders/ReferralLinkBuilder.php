<?php

namespace App\Services\Builders;

use Illuminate\Database\Eloquent\Builder;

class ReferralLinkBuilder extends Builder
{

    public function countUsers() {

       return $this->withCount('users');
    }
}
