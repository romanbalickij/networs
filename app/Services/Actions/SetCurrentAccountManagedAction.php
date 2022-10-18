<?php


namespace App\Services\Actions;


class SetCurrentAccountManagedAction
{

    public function execute(int $managed_account_id) {

        user()->id  == $managed_account_id
            ? user()->setCurrentAccount(null)
            : user()->setCurrentAccount($managed_account_id);
    }

}
