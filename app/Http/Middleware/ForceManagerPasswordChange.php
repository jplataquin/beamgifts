<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class ForceManagerPasswordChange
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $manager = Auth::guard('manager')->user();

        if ($manager && $manager->must_change_password) {
            // Exclude the password change route and logout route to avoid loops
            if (!$request->routeIs('manager.password.edit') && !$request->routeIs('manager.password.update') && !$request->routeIs('manager.logout')) {
                return redirect()->route('manager.password.edit');
            }
        }

        return $next($request);
    }
}
