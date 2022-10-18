<?php


namespace App\Models\Traits\File;

use App\Services\Specification\VisibleSpecification;

trait HasEntity
{

    public function isEntityPPV() :bool {

        return $this->entity->is_ppv == 1 ?? false;
    }

    public function allowedFile() {

       return (new VisibleSpecification())->isSatisfiedBy($this);
    }

    public function isUnlockedFor($user) {


        return $this->entity
            ->payments
            ->isPay($user);
    }
}
