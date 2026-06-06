<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        // If the user is not logged in, or their role is not in the allowed list
        if (!Auth::check() || !in_array(Auth::user()?->role, $roles)) {
            abort(403, 'Unauthorized action. You do not have the right permissions.');
        }

        return $next($request);
    }
// app/Http/Middleware/Authenticate.php

protected function redirectTo(Request $request): ?string
{
    // Ensure this points to the named route 'login'
    return $request->expectsJson() ? null : route('login');
}

}