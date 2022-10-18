<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\BaseController;
use App\Http\Requests\User\UpdateRequest;
use App\Http\Resources\Account\AccountInformationResource;
use App\Http\Resources\SettingResource;
use App\Http\Resources\User\ProfileResource;
use App\Services\Actions\UpdateUserAction;
use Illuminate\Http\Request;

class ProfileController extends BaseController
{

    public function profile() {

        $user = user()
            ->load('accountManagers.owner');

      return $this->respondWithSuccess(

             (new ProfileResource($user))->except('verified')
      );
    }

    public function update(UpdateRequest $request, UpdateUserAction $updateUserAction) {

       $user = $updateUserAction->execute(
            $request->getDto(),
           env('APP_ENV') == 'production' ? $request->get('avatar') : $request->file('avatar'),
      env('APP_ENV') == 'production' ? $request->get('background') : $request->file('background')
        );

        return $this->respondWithSuccess(

            new ProfileResource($user->load('accountManagers.owner'))
        );
    }

    public function showSettings() {

        return $this->respondWithSuccess(

            SettingResource::collection(user()->getAllSettings())
        );
    }

    public function updateSettings(Request $request) {

        user()->setSettings($request->all());

        return $this->respondWithSuccess(

            SettingResource::collection(user()->getAllSettings())
        );
    }

    public function delete() {

       user()->deleteProfile();

       return $this->respondOk('The Account deleted successfully');
    }

    public function info() {

        $user= \user()
            ->loadCount(['informings' => fn($q) => $q->unread()])
            ->load(['subscribers' => fn($q) => $q->withCount(['user' => fn($q) => $q->online()]) ]);

        return $this->respondWithSuccess(

               new AccountInformationResource($user)
        );
    }

    public function removeAvatar() {

        user()->deleteProfilePhoto();

        return $this->respondOk('The Avatar deleted successfully');
    }

    public function removeBackground() {

        user()->deleteBackground();

        return $this->respondOk('The Background deleted successfully');
    }
}
