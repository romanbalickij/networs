<?php


namespace App\Services\Actions;


use App\Models\User;

class UserCalculateRatingAction
{


    public function handler(User $currentUser) {

        $users = User::orderBy('count_subscribers', 'DESC')
            ->get();

        $index = $users
            ->filter(fn ($user) => $user->id == $currentUser->id)
            ->keys()
            ->first();

        return number_format(($index / $users->count()) * 100, 0);
    }
}
