<?php

namespace App\Models\Traits\Donation;

use App\Models\Traits\General\RelationshipOwner;
use App\Models\Traits\General\RelationshipUser;

trait HasUser
{
    use RelationshipUser;
    use RelationshipOwner;

    protected function forgeKeyModel() {

        return 'creator_id';
    }
}
