<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class EnsureIsMasterAdmin
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Session::get('is_master_admin')) {
            return redirect()->route('unlock.login')->with('error', 'Unauthorized access.');
        }

        return $next($request);
    }
}
