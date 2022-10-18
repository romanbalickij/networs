<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\BaseController;
use App\Http\Requests\User\SetAccountManagedRequest;
use App\Http\Resources\User\ProfileResource;
use App\Models\User;
use App\Services\Actions\SetCurrentAccountManagedAction;
use Illuminate\Support\Facades\Auth;
use phpDocumentor\Reflection\DocBlock\Tags\Uses;

class ProfileManagedController extends BaseController
{


    public function index()
    {
        return $this->respondWithSuccess(

            ((new ProfileResource(user()->load('accountManagers.owner')))
            )->only('id', 'locale', 'managed_accounts')
        );
    }

    public function setManaged(SetAccountManagedRequest $request, SetCurrentAccountManagedAction $accountManagedAction) {

        $accountManagedAction->execute($request->managed_account_id);

//         $user = \user();
//
//         return $this->respondWithSuccess([
//             'user' => $user,
//             'current' => Auth::id()
//         ]);

        return $this->respondWithSuccess('Success');
    }
}
