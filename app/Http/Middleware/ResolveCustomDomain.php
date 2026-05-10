<?php

namespace App\Http\Middleware;

use App\Models\User;
use App\Models\Website;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ResolveCustomDomain
{
    /**
     * Check if the request host matches a verified custom domain.
     * If so, resolve the website and inject it into the request.
     * If not a custom domain, pass through to normal routes.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $host = $request->getHost();
        $platformDomain = parse_url(config('app.url'), PHP_URL_HOST) ?: 'localhost';

        // If this is the platform domain or a subdomain of it, skip
        if ($host === $platformDomain || str_ends_with($host, '.' . $platformDomain)) {
            return $next($request);
        }

        // Look up the host as a custom domain
        $website = Website::where('custom_domain', $host)
            ->whereNotNull('custom_domain_verified_at')
            ->first();

        if (! $website) {
            // Not a recognized custom domain — pass through (will 404 naturally)
            return $next($request);
        }

        // Check if website is active
        if (! $website->is_active) {
            abort(404, 'Website is not available.');
        }

        // Check owner
        $user = User::find($website->user_id);

        if (! $user) {
            abort(404, 'Website not found.');
        }

        // Check if owner is blocked
        if ($user->is_blocked) {
            return response()->view('public.suspended', [
                'reason' => 'Pemilik website telah ditangguhkan oleh admin.',
            ], 403);
        }

        // Check if owner subscription is expired
        if ($user->subscription_status === 'Expired' || ($user->subscription_expires_at && $user->subscription_expires_at->isPast())) {
            return response()->view('public.suspended', [
                'reason' => 'Langganan pemilik website telah berakhir.',
            ], 403);
        }

        // Check owner has active subscription
        if ($user->subscription_status !== 'Active') {
            return response()->view('public.suspended', [
                'reason' => 'Website belum aktif.',
            ], 403);
        }

        // Eager-load relations
        $website->load(['websiteSetting', 'vehicles' => fn ($q) => $q->orderBy('created_at', 'desc'), 'tourPackages' => fn ($q) => $q->orderBy('created_at', 'desc')]);

        // Store website and flag in request for controller access
        $request->attributes->set('website', $website);
        $request->attributes->set('is_custom_domain', true);

        return $next($request);
    }
}
