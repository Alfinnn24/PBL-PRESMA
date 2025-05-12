<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthorizeUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        $user = $request->user();

        if (!$user) {
            // Jika user belum login, redirect ke halaman login
            return redirect()->route('login');
            // Atau bisa juga:
            // abort(401, 'Unauthenticated');
        }

        if (in_array($user->getRole(), $roles)) {
            return $next($request);
        }

        // Jika role tidak sesuai
        abort(403, 'Forbidden. Kamu tidak punya akses ke halaman ini');
    }
}
