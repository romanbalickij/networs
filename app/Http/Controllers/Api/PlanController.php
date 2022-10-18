<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Plan\PlanRequest;
use App\Http\Resources\User\PlanCollection;
use App\Http\Resources\User\PlanResource;
use App\Models\Plan;
use App\Services\Actions\PlanAddAction;
use App\Services\Actions\PlanUpdateAction;


class PlanController extends BaseController
{

    public function index() {

        return $this->respondWithSuccess(

            new PlanCollection(user()->plans)
        );
    }

    public function store(PlanRequest $request, PlanAddAction $planAddAction) {

        $newPlan = $planAddAction->execute($request->getDto());

        return $this->respondWithSuccess(

            new PlanResource($newPlan)
        );
    }

    public function update(PlanRequest $request, Plan $plan, PlanUpdateAction $planUpdateAction) {

        $this->authorize('update', $plan);

        $plan = $planUpdateAction->execute($plan, $request->getDto());

        return $this->respondWithSuccess(

            new PlanResource($plan)
        );
    }

    public function destroy(Plan $plan) {

        $this->authorize('delete', $plan);

        $plan->delete();

        return $this->respondOk('The plan deleted successfully');
    }
}
