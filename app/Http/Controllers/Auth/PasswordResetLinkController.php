<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

class PasswordResetLinkController extends Controller
{

    public function store(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status == Password::RESET_LINK_SENT
                    ? $this->sendResetLinkResponse($status)
                    : $this->sendResetLinkFailedResponse($status);
    }

    protected function sendResetLinkResponse($response)
    {
        return response()->json(['status' => trans($response)],200);
    }

    protected function sendResetLinkFailedResponse($response) {

        return response()->json(['email' => trans($response)],422);

    }
}
