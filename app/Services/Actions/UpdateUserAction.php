<?php


namespace App\Services\Actions;


use App\Services\DataTransferObjects\UserDto;
use Illuminate\Support\Facades\Hash;

class UpdateUserAction
{

    public function execute($payload, $avatar, $background) {

         $user = tap(user())->update(
              collect($payload)
                  ->except('password', 'avatar', 'background', 'balance')
                  ->toArray());

        $user->generatePassword($payload['password'] ?? null);

        //if production send string if local or dev send file


        env('APP_ENV') == 'production'
            ? $this->setFiles($user, $avatar, $background)
            : ($user->generateProfilePhoto($avatar) and $user->generateBackground($background));

        return $user;
    }

    protected function setFiles($user, $avatar, $background) {

        if($avatar) {$user->update(['avatar' => $avatar]);}

        if($background) {$user->update(['background' => $background]);}
    }
}
