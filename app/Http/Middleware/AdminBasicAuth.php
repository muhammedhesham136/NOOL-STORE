<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminBasicAuth
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = (string) env('ADMIN_BASIC_USER', '');
        $pass = (string) env('ADMIN_BASIC_PASSWORD', '');

        // If not configured, don't block locally.
        if ($user === '' || $pass === '') {
            return $next($request);
        }

        $providedUser = (string) $request->getUser();
        $providedPass = (string) $request->getPassword();

        if (!hash_equals($user, $providedUser) || !hash_equals($pass, $providedPass)) {
            return response('Unauthorized', 401, [
                'WWW-Authenticate' => 'Basic realm="Nool Admin"',
            ]);
        }

        return $next($request);
    }
}

