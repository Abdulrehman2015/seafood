<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ApprovedCustomerMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        if (!$user) {
            return redirect()->route('login');
        }

        if ($user->needsApproval()) {
            if ($user->isPending()) {
                return redirect()->route('approval.pending');
            }
            if ($user->isRejected()) {
                return redirect()->route('approval.rejected');
            }
        }

        return $next($request);
    }
}
