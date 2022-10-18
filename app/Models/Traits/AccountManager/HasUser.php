<?php

namespace App\Models\Traits\AccountManager;

use App\Models\Traits\General\RelationshipOwner;
use App\Models\Traits\General\RelationshipUser;


trait HasUser
{
    use RelationshipUser;
    use RelationshipOwner;


    protected function forgeKeyModel() {

        return 'manages_user_id';
    }
}
