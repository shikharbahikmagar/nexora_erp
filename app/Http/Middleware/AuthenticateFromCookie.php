<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class AuthenticateFromCookie
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->cookie('auth_token');

        Log::error('COOKIE MIDDLEWARE HIT', [
            'cookie' => $token,
            'bearer_before' => $request->bearerToken(),
        ]);

        if (! $request->bearerToken() && $token) {
            $request->headers->set(
                'Authorization',
                'Bearer ' . urldecode($token)
            );
        }

        Log::error('AFTER TOKEN SET', [
            'bearer_after' => $request->bearerToken(),
        ]);

        return $next($request);
    }
}
