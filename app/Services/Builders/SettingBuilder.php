<?php

namespace App\Services\Builders;

use Illuminate\Database\Eloquent\Builder;

class SettingBuilder extends Builder
{


    public function autoProlongSubscription() {

        return $this->where(fn ($q) => $q->where('key', 'auto_prolong_subscription')->where('value', true));
    }
}
