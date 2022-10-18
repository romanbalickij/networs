<?php


namespace App\Services\Actions;


use App\Models\Plan;
use App\Services\DataTransferObjects\PlanDto;

class PlanAddAction
{

    public function execute(PlanDto $planDto) {

        return $this->add($planDto);
    }
    protected function add($plan) {

        return Plan::create(collect($plan)->merge([
            'user_id' => user()->id

        ])->toArray());
    }
}
