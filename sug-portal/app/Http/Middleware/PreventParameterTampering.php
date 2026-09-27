<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PreventParameterTampering extends Middleware
{
    /**
     * Handle an incoming request.
     *
     * Ensures that critical IDs (like payment_id or student_id) in the request
     * match the authenticated user's ownership to prevent IDOR (Insecure Direct Object Reference).
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Example: If the request is for a receipt or a specific student profile
        if ($request->route('payment')) {
            $payment = \App\Models\Payment::findOrFail($request->route('payment'));
            if ($payment->student_id !== auth()->id()) {
                abort(403, 'Unauthorized access to this resource.');
            }
        }

        if ($request->route('student') && auth()->user()->hasRole('student')) {
            if ($request->route('student')->id !== auth()->id()) {
                abort(403, 'You cannot view another student\'s profile.');
            }
        }

        return $next($request);
    }
}
