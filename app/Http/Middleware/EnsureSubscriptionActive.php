<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureSubscriptionActive
{
    /**
     * Handle an incoming request.
     *
     * Only allow access if the authenticated driver has an 'Active' subscription.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user('web');

        // Allow impersonating admins to bypass subscription check
        if ($request->session()->has('impersonating_admin')) {
            return $next($request);
        }

        if (! $user || $user->subscription_status !== 'Active') {
            if ($user && $user->subscription_status === 'Pending') {
                if (! $user->websites()->exists()) {
                    return redirect()->route('onboarding.subdomain');
                }

                return redirect()->route('onboarding.paywall');
            }

            if ($user && $user->subscription_status === 'Expired') {
                return redirect()->route('dashboard');
            }

            return redirect()->route('login');
        }

        return $next($request);
    }
}
