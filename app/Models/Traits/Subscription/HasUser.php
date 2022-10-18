<?php

namespace App\Models\Traits\Subscription;

use App\Models\Traits\General\RelationshipUser;
use App\Models\User;

trait HasUser
{
    use RelationshipUser;

    public function owner() {

        return $this->belongsTo(User::class, 'creator_id');
    }

}
