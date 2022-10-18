<?php

namespace App\Models\Traits\User;

use App\Models\Plan;

trait HasPlans
{

    public function plans() {

        return $this->hasMany(Plan::class);
    }

    public function createFreePlan() {

        return $this->plans()->create([
            'name'         => 'Free subscription',
            'discount'     => 0,
            'monthly_cost' => 0
        ]);
    }

}
