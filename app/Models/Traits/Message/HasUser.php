<?php

namespace App\Models\Traits\Message;

use App\Models\Traits\General\RelationshipOwner;
use App\Models\User;

trait HasUser
{
    use RelationshipOwner;

    protected function forgeKeyModel() {

        return 'user_id';
    }

    public function isAuthoredBy($id) : bool
    {
        return $this->user_id === $id;
    }
}
