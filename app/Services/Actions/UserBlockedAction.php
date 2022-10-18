<?php


namespace App\Services\Actions;


use App\Models\User;

class UserBlockedAction
{

    public function execute(User $user) {

        return user()->blocked($user->id);
    }
}
