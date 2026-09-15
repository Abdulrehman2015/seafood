<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsurePendingUserRestricted
{
    /**
     * Handle an incoming request.
     * Restricts logged-in users with 'pending' approval status to only the /pending-approval page.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check()) {
            $user = auth()->user();

            if ($user->isPending()) {
                // Allowed paths and routes for pending users:
                // 1. The pending approval page
                // 2. Logout route (POST /logout)
                // 3. API polling endpoint to check approval status
                if (
                    $request->routeIs('approval.pending') ||
                    $request->routeIs('approval.check_status') ||
                    $request->routeIs('logout') ||
                    $request->is('pending-approval') ||
                    $request->is('logout') ||
                    $request->is('api/check-approval-status')
                ) {
                    return $next($request);
                }

                if ($request->expectsJson()) {
                    return response()->json([
                        'error'    => 'Account pending approval.',
                        'redirect' => route('approval.pending'),
                    ], 403);
                }

                return redirect()->route('approval.pending');
            }
        }

        return $next($request);
    }
}
