<?php


namespace App\Services\Actions;


use App\Models\User;

class UserUnblockAction
{

    public function execute(User $user) {

        return user()->unblock($user->id);
    }
}
