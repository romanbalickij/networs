<?php

namespace App\Policies;

use App\Models\Plan;
use App\Models\Post;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PlanPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function delete (User $user, Plan $plan) {

        return $plan->isAuthoredBy($user);
    }

    public function update (User $user, Plan $plan) {

        return $plan->isAuthoredBy($user);
    }
}
