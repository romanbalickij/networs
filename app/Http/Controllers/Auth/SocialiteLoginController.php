<?php

namespace App\Http\Controllers\Auth;

use App\Enums\TrackFnType;
use App\Enums\UserType;
use App\Http\Controllers\BaseController;
use App\Models\SocialIdentity;
use App\Models\User;
use App\Services\Actions\SeReferralAction;
use App\Services\Actions\SetExternalTrackerAction;
use App\Services\Actions\TrackFnsAction;
use App\Services\Chat\ConversationSupport;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;
use Laravel\Socialite\Facades\Socialite;
use Tymon\JWTAuth\Facades\JWTAuth;

class SocialiteLoginController extends BaseController
{
    public function redirectToProvider($provider)
    {

        Session::put('external_tracker_id', request()->get('external_tracker_id'));

        return Socialite::driver($provider)->stateless()->redirect();
    }

    public function handleProviderCallback($provider)
    {

        try {
            $providerUser = Socialite::driver($provider)->stateless()->user();

        } catch (\Exception $e) {

            return $this->respondError($e->getMessage());
        }

        $authUser = $this->findOrCreateUser($providerUser, $provider);

        $token = JWTAuth::fromUser($authUser);

        $response = json_encode([
            'token' => $token,
            'role'  => $authUser->role
        ]);

        return redirect(env('GOOGLE_REDIRECT_BACK_TO') ?? '/')
            ->withCookie(cookie("socialite", $response, 30, null, null, false, false));
    }

    private function findOrCreateUser($providerUser, $provider)
    {

        $account = SocialIdentity::whereProviderName($provider)
            ->whereProviderId($providerUser->getId())
            ->first();

        $role = UserType::USER;

        if ($account) {
            $user = $account->user;

        } else {
            $user = User::whereEmail($providerUser->getEmail())->first();


            if (!$user) {
                $credentials = splitName($providerUser->getName());

                $user = User::create([
                    'email'             => $providerUser->getEmail(),
                    'name'              => $credentials['name'],
                    'surname'           => $credentials['surname'],
                    'email_verified_at' => Carbon::now(),
                    'role'              => $role
                ]);

                //create  empty support room
                $chat = app(ConversationSupport::class);
                $chat->createRoom($user);

                $user->generateNickname();
                $user->createFreePlan();
                $user->createDefaultGroup();

                // check if has cookie referral
                app(SeReferralAction::class)->handle($user);

            }

            $user->identities()->create([
                'provider_id' => $providerUser->getId(),
                'provider_name' => $provider,
            ]);

        }

        $user->emailVerify();

        app(SetExternalTrackerAction::class)->execute($user, Session::get('external_tracker_id'));

        app(TrackFnsAction::class)->handler([TrackFnType::SIGNUP], $user->external_tracker_id);

        return $user;
    }

}
