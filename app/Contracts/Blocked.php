<?php


namespace App\Contracts;


use App\Models\User;

interface Blocked
{

    public function isBlocked(User $user, int $blockedId);
    public function blocked(User $user,   int $id);
    public function unblocked(User $user, int $id);
}
