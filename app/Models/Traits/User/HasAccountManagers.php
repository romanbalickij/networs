<?php

namespace App\Models\Traits\User;

use App\Models\AccountManager;
use App\Models\User;

trait HasAccountManagers
{

    public function accountManagers() {

        return $this->hasMany(AccountManager::class);
    }

    public function ownerAccountManagers() {

        return $this->hasMany(AccountManager::class, 'manages_user_id');
    }

    public function managerUsers() {

        return $this->hasManyThrough(

            User::class,
            AccountManager::class,
            'manages_user_id',
            'id',
            'id',
            'user_id'
        );
    }

    public function deleteAccountManager($manager) :void {

        $this->ownerAccountManagers()
            ->where('user_id', $manager->id)
            ->delete();
    }

    public function setCurrentAccount(?int $account) {

       return $this->update(['current_account' => $account]);
    }

    public function isActiveCurrentAccount(int $account) {

        return $this->current_account == $account;
    }
}
