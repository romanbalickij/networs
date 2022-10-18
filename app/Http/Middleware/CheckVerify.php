<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckVerify
{

    public function handle(Request $request, Closure $next)
    {
        if(auth()->check() && (!user()->isAccountVerified())) {

            return response()->json(['error' => 'Your account has not been verified'], 403);
        }
        return $next($request);
    }
}
