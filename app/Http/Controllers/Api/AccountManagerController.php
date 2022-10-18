<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\BaseController;
use App\Http\Requests\AccountManager\AccountManagerRequest;
use App\Http\Resources\AccountManager\AccountManagerCollection;
use App\Models\User;
use App\Services\Actions\AddAccountManagerAction;

class AccountManagerController extends BaseController
{

    public function index() {

        return $this->respondWithSuccess(

            new AccountManagerCollection(user()->managerUsers)
        );
    }

    public function store(AccountManagerRequest $request, AddAccountManagerAction $accountManagerAction) {

        $accountManagerAction->execute($request->payload());

        return $this->respondWithSuccess('Account manager added successfully');

    }

    public function destroy(User $accountManager) {

        user()->deleteAccountManager($accountManager);

        return $this->respondOk('The AccountManager deleted successfully');
    }
}
