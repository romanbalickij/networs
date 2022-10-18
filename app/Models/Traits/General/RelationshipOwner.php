<?php

namespace App\Models\Traits\General;

use App\Models\User;

trait RelationshipOwner
{
    public function owner() {

        return $this->belongsTo(User::class, $this->forgeKeyModel());
    }

}
