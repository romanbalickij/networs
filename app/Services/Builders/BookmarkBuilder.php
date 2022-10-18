<?php

namespace App\Services\Builders;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class BookmarkBuilder extends Builder
{

    public function typeBookmark($type, Model $entity) {

        $this->where('entity_type', $type)->where('entity_id', $entity->id);

        return $this;
    }

}
