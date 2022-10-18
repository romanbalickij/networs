<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminMiddleware
{


    public function handle(Request $request, Closure $next)
    {

        if(!user()->isAdmin()) {

            return response()->json([
                'error' => 401,
                'message' => 'Not authorized'
            ], 401);
        }

        return $next($request);
    }
}
