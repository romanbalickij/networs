<?php

namespace App\Models\Traits\General;

use App\Models\User;

trait RelationshipUser
{

    public function user() {

        return $this->belongsTo(User::class, 'user_id',  'id');
    }
}
