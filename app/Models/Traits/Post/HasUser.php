<?php

namespace App\Models\Traits\Post;

use App\Models\Traits\General\RelationshipOwner;
use App\Models\Traits\General\RelationshipUser;
use App\Models\User;

trait HasUser
{
    use RelationshipUser,
        RelationshipOwner;

    protected function forgeKeyModel() {

        return 'user_id';
    }

    public function isAuthoredBy($id) : bool
    {
        return $this->user_id === $id;
    }
}
