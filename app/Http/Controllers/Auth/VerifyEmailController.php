<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\BaseController;
use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;

class VerifyEmailController extends BaseController
{

    public function verify(Request $request, $id)
    {
        $user = User::findOrFail($id);

        if(! URL::hasValidSignature($request)){

            return $this->respondError("Invalid verification link or signature",422);
        }

        if ($user->hasVerifiedEmail()) {
            return $this->respondError("Email address already verified", 422);
        }

        if ($user->markEmailAsVerified()) {
            event(new Verified($user));
        }

    //    return redirect('/login?verified=1');

        return redirect(env('FRONT_URL').'/profile?verified=true');

    }

    public function resend(Request $request) {

        $this->validate($request, [
            'email' => ['email', 'required']
        ]);

        $user = User::where('email', $request->email)->first();

        if(!$user) {
            return $this->respondError('No user could be found with this email address',404);
        }

        if($user->hasVerifiedEmail()) {
            return $this->respondError('Email address already verified',422);
        }

        $user->sendEmailVerificationNotification();

        return $this->respondWithSuccess('Verification link resent');
    }
}
