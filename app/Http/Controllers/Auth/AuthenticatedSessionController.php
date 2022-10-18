<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\BaseController;
use App\Http\Resources\User\UserResource;
use App\Models\User;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Validator;

class AuthenticatedSessionController extends BaseController
{

    public function store(Request $request)
    {

         $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);


        if ($validator->fails()) {
            return $this->respondError($validator->errors(), 422);
        }

        if (! $token = auth()->attempt($validator->validated())) {
            return $this->respondError('Unauthenticated', 451);
        }

//        $user = User::where('email', $request['email'])->firstOrFail();
//
//        if($user instanceof MustVerifyEmail && ! $user->hasVerifiedEmail()) {
//              return $this->respondError("You need to verify your email account", 422);
//         }

        return $this->createNewToken($token);
    }

    public function destroy(Request $request)
    {
        auth()->user()->tokens()->delete();

        return $this->respondWithSuccess('Successfully logged out');
    }

    protected function createNewToken($token) {

        return response()->json([
            'access_token' => $token,
            'token_type'   => 'bearer',
            'email'        => auth()->user()->email,
            'expires_in'   => auth()->getPayload()->get('exp'),

        ]);
    }

    public function availableAccount(Request $request) {

        $user = User::whereEmail($request->get('email'))->first();

        if(!$user) {

            return $this->respondError([], 404);
        }
        return $this->respondWithSuccess(

            (new UserResource($user))->only('id', 'name', 'surname', 'role', 'email', 'avatar')
        );

    }
}
