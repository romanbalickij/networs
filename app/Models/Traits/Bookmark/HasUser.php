<?php

namespace App\Models\Traits\Bookmark;

use App\Models\Traits\General\RelationshipOwner;

trait HasUser
{
    use RelationshipOwner;


    protected function forgeKeyModel() {

        return 'user_id';
    }
}
