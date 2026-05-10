<?php

namespace App\Http\Middleware;

use App\Models\User;
use App\Models\Website;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ValidateSubdomain
{
    /**
     * Validate that the subdomain:
     * 1. Exists in the websites table
     * 2. Has is_active = true
     * 3. Owner is not blocked
     * 4. Owner has an active subscription (not expired)
     */
    public function handle(Request $request, Closure $next): Response
    {
        $subdomain = $request->route('subdomain');

        if (! $subdomain) {
            abort(404, 'Website not found.');
        }

        // Find website by subdomain
        $website = Website::where('subdomain', $subdomain)->first();

        if (! $website) {
            abort(404, 'Website not found.');
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

        // Check if owner is blocked → show suspended page
        if ($user->is_blocked) {
            return response()->view('public.suspended', [
                'reason' => 'Pemilik website telah ditangguhkan oleh admin.',
            ], 403);
        }

        // Check if owner subscription is expired → show suspended page
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

        // Share website data with all views
        $website->load(['websiteSetting', 'vehicles' => fn ($q) => $q->orderBy('created_at', 'desc'), 'tourPackages' => fn ($q) => $q->orderBy('created_at', 'desc')]);

        // Store in request for controller access
        $request->attributes->set('website', $website);

        return $next($request);
    }
}
