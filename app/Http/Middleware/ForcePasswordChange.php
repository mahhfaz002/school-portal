<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ForcePasswordChange
{
    public function handle(Request $request, Closure $next): Response
    {
        // 1. Only run if the user is logged in
        if (auth()->check()) {

            // 2. If they are flagged to change password
            if (auth()->user()->must_change_password) {

                // 3. ALLOW access to the change-password routes and the logout route
                // This is the "Escape Hatch" that breaks the loop
                if ($request->routeIs('password.change.notice') ||
                    $request->routeIs('password.change.update') ||
                    $request->is('logout')) {
                    return $next($request);
                }

                // 4. Otherwise, redirect them to the change-password page
                return redirect()->route('password.change.notice');
            }
        }

        return $next($request);
    }
}
