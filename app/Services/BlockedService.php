<?php


namespace App\Services;


use App\Contracts\Blocked;
use App\Models\User;

class BlockedService implements Blocked
{

    public function isBlocked($author, int $userId)
    {
        return $author->creatorBlocking->contains(fn ($blockedUsers) => $blockedUsers->user_id === $userId);
    }

    public function blocked(User $user, int $id)
    {
        return $user->creatorBlocking()->firstOrCreate(['user_id' => $id]);
    }

    public function unblocked(User $user, int $id)
    {
       return $user->creatorBlocking()->where('user_id', $id)->delete();
    }
}
