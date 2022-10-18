<?php

namespace App\Models\Traits\User;

trait HasBanned
{

    public function isBanned(): bool
    {
        return $this->blocked;
    }

    public function isNotBanned(): bool
    {
        return !$this->isBanned();
    }
}
