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

            // Admins are never restricted
            if ($user->isAdmin()) {
                return $next($request);
            }

            // 1. Rejected users: restrict to /account-rejected
            if ($user->isRejected()) {
                if (
                    $request->routeIs('approval.rejected') ||
                    $request->routeIs('approval.check_status') ||
                    $request->routeIs('logout') ||
                    $request->is('account-rejected') ||
                    $request->is('logout') ||
                    $request->is('api/check-approval-status')
                ) {
                    return $next($request);
                }

                if ($request->expectsJson()) {
                    return response()->json([
                        'error'    => 'Account application was not approved.',
                        'redirect' => route('approval.rejected'),
                    ], 403);
                }

                return redirect()->route('approval.rejected');
            }

            // 2. Pending users: restrict to /pending-approval
            if ($user->isPending()) {
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
