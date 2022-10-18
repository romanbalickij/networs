<?php

namespace App\Models\Traits\AdCampaign;

use App\Models\Traits\General\RelationshipUser;
use App\Models\User;

trait HasUser
{
    use RelationshipUser;

    public function isAuthoredBy(User $author) : bool
    {
        return $this->user->id === $author->id;
    }
}
