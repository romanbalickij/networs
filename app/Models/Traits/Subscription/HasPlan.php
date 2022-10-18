<?php

namespace App\Models\Traits\Subscription;

use App\Models\Plan;

trait HasPlan
{

    public function plan() {

        return $this->belongsTo(Plan::class);
    }
}
