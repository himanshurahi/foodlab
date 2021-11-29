<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CheckRestaurantStatus
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (Auth::guard('restaurant')->check()) {
            $restaurant = Auth::guard('restaurant')->user();
            if ($restaurant->status && $restaurant->tv  && $restaurant->sv && $restaurant->ev) {
                return $next($request);
            } else {
                return redirect()->route('restaurant.authorization');
            }
        }
        abort(403);
    }
}
