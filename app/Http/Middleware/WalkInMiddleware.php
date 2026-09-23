<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class WalkInMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        // Ensure walk-in session is active so public visitors can browse walk-in prices and products seamlessly
        if (!session('walkin_session')) {
            session([
                'walkin_session'    => true,
                'walkin_started_at' => now()->toDateTimeString(),
            ]);
        }

        return $next($request);
    }
}
