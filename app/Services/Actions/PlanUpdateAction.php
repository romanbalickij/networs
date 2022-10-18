<?php


namespace App\Services\Actions;


use App\Models\Plan;
use App\Services\DataTransferObjects\PlanDto;

class PlanUpdateAction
{

    public function execute(Plan $plan, PlanDto $planDto) {

        return tap($plan)->update($planDto->toArray());
    }

}
