<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class WalkInMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        // Ensure the request has a valid walk-in session
        if (!session('walkin_session')) {
            return redirect()->route('walkin.entry');
        }

        return $next($request);
    }
}
