<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RateLimitAdminRequests extends Middleware
{
    /**
     * Handle an incoming request.
     *
     * Specifically throttles administrative endpoints more aggressively
     * to prevent brute-force attempts on management functions.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->is('admin/*')) {
            // Use a specific cache key for admin throttling
            $key = 'admin_limit:' . $request->ip();

            // Limit to 60 requests per minute for admin area
            if (\Illuminate\Support\Facades\RateLimiter::tooManyAttempts($key, 60)) {
                return response()->json(['error' => 'Too many administrative requests. Please slow down.'], 429);
            }

            \Illuminate\Support\Facades\RateLimiter::hit($key);
        }

        return $next($request);
    }
}
