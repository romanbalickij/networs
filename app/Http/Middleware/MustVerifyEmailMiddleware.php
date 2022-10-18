<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MustVerifyEmailMiddleware
{

    public function handle(Request $request, Closure $next)
    {

        if(Auth::check() and (user() instanceof MustVerifyEmail && ! user()->hasVerifiedEmail())) {
            return response()->json([
                'error' => 'You need to verify your email account'
            ], 422);
        }
        return $next($request);
    }
}
