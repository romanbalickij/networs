<?php

namespace App\Models\Traits\User;

use App\Contracts\Blocked;
use App\Models\BlockedUser;

trait HasBlockedUsers
{

  public function blockedUsers() {

        return $this->hasMany(BlockedUser::class);
    }

  public function creatorBlocking() {

        return $this->hasMany(BlockedUser::class, 'bloquee_id');
  }

  public function isBlockedUser($user) {

      return app(Blocked::class)->isBlocked($this, $user->id);
  }

  public function blocked(int $id) {

      return app(Blocked::class)->blocked($this, $id);
  }

  public function unblock(int $id) {

    return app(Blocked::class)->unblocked($this, $id);
  }

  public function blockedUserList() {

  return $this->creatorBlocking();
}

  }
