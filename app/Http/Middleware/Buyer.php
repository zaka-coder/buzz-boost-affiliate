<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class Buyer
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // if (Auth::check() && Auth::user()->active_role == 'buyer') {
        if (Auth::check() && Auth::user()->hasRole('buyer') && Auth::user()->profile) {
            return $next($request);
        }

        abort(403, 'Unauthorized.');
    }
}
