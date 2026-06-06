<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsurePasswordIsChanged
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::guard('partner')->user();

        if ($user && $user->must_change_password) {
            // Determine the target route based on role
            $routeName = $user->role === 'manager' ? 'manager.password.edit' : 'partner.password.edit';
            $updateRoute = $user->role === 'manager' ? 'manager.password.update' : 'partner.password.update';

            // Exclude the password change routes and logout route
            if (!$request->routeIs($routeName) && !$request->routeIs($updateRoute) && !$request->routeIs('partner.logout')) {
                return redirect()->route($routeName)->with('info', 'Please change your password to continue.');
            }
        }

        return $next($request);
    }
}
