<?php

namespace App\Services\Builders;

use Illuminate\Database\Eloquent\Builder;

class PaymentMethodBuilder extends Builder
{


    public function whereDefault() {

        return $this->where('default', true);
    }
}
