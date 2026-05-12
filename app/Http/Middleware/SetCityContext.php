<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\City;
use Illuminate\Support\Facades\URL;

class SetCityContext
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $citySlug = $request->route('city_slug');

        if ($citySlug) {
            $city = City::where('slug', $citySlug)->where('is_active', true)->first();

            if (!$city) {
                abort(404, 'City not found.');
            }

            // Share city globally in the app
            app()->instance('current_city', $city);
            
            // Set default URL parameter for city_slug
            URL::defaults(['city_slug' => $citySlug]);
        }

        return $next($request);
    }
}
