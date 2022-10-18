<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AllowedChatSupport
{

    public function handle(Request $request, Closure $next)
    {
        $chat = $request->route('chat');

        if(user()->isBanned() and !$chat->isSupport()) {

            return response()->json(['error' => 'Your account has been blocked '], 403);
        }

        return $next($request);
    }
}
