<?php

namespace App\Models\Traits\ReferralLink;

use App\Models\Traits\General\RelationshipUser;
use App\Models\User;

trait HasUser
{

    use RelationshipUser;

    //todo
    public function users() {

        return $this->hasMany(User::class);
    }
}
