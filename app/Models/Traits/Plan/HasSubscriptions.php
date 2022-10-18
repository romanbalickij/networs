<?php


namespace App\Models\Traits\Plan;


use App\Models\Subscription;
use Illuminate\Support\Facades\Auth;

trait HasSubscriptions
{

    public function subscription() {

        return $this
            ->hasOne(Subscription::class)
            ->where('user_id', Auth::id())
            ->paid()
            ->withinLastMonth();
    }

    public function getIsAuthUserSubscriberPlanAttribute() {

        return (bool)$this ->subscription ;
    }


}
