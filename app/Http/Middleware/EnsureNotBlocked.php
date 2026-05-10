<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureNotBlocked
{
    /**
     * Handle an incoming request.
     *
     * Prevent blocked users from accessing the driver dashboard.
     * Skipped during impersonation (admin can still view blocked users).
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user('web');

        // Skip check during impersonation
        if ($request->session()->has('impersonating_admin')) {
            return $next($request);
        }

        if ($user && $user->is_blocked) {
            Auth::guard('web')->logout();

            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')
                ->with('error', 'Akun Anda telah ditangguhkan. Hubungi admin untuk informasi lebih lanjut.');
        }

        return $next($request);
    }
}
