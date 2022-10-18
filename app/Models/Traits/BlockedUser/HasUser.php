<?php

namespace App\Models\Traits\BlockedUser;

use App\Models\Traits\General\RelationshipUser;
use App\Models\User;

trait HasUser
{
    use RelationshipUser;

    public function bloquee() {

        return $this->belongsTo(User::class, 'bloquee_id');
    }

}
