<?php

namespace App\Http\Controllers\Auth;

use App\Enums\ChatType;
use App\Enums\TrackFnType;
use App\Http\Controllers\BaseController;
use App\Http\Resources\User\UserResource;
use App\Models\User;
use App\Rules\RecaptchaV3;
use App\Services\Actions\SeReferralAction;
use App\Services\Actions\SetExternalTrackerAction;
use App\Services\Actions\TrackFnsAction;
use App\Services\Chat\ConversationSupport;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class RegisteredUserController extends BaseController
{

    public function create()
    {
        return view('auth.register');
    }


    public function store(Request $request)
    {

        $request->validate([
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required'],
            'g-recaptcha-response' => ['required', new RecaptchaV3],
        ]);

        $user = User::create([
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'name'     => $request->name ?? generateNameUser(),
        ]);

        $user->generateNickname();

        $user->createFreePlan();

        $user->createDefaultGroup();

        $user->sendEmailVerificationNotification();

        app(SetExternalTrackerAction::class)->execute($user, $request->external_tracker_id);

        event(new Registered($user));

        Auth::login($user);

        $chat = app(ConversationSupport::class);

        $chat->createRoom($user);

        app(SeReferralAction::class)->handle($user);

        app(TrackFnsAction::class)->handler([TrackFnType::SIGNUP], $user->external_tracker_id);

        return $this->respondWithSuccess(

            (new UserResource($user))->except('reactions_count', 'description', 'activity_status', 'url')
        );
    }
}
