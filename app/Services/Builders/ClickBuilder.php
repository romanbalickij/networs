<?php

namespace App\Services\Builders;

use Illuminate\Database\Eloquent\Builder;

class ClickBuilder extends Builder
{

    public function registerUser() {

       return $this->where('user_ip' , '!=', null);
    }
}
