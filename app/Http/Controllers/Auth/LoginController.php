<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * Show the driver login form.
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Handle a driver login request.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::guard('web')->attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            $user = Auth::guard('web')->user();

            // Check if user is blocked
            if ($user->is_blocked) {
                Auth::guard('web')->logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return back()->withErrors([
                    'email' => 'Akun Anda telah ditangguhkan. Hubungi admin untuk informasi lebih lanjut.',
                ])->onlyInput('email');
            }

            // Redirect based on subscription status
            return $this->redirectBasedOnStatus($user);
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->onlyInput('email');
    }

    /**
     * Log the driver out.
     */
    public function logout(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    /**
     * Redirect user based on their subscription status.
     */
    private function redirectBasedOnStatus($user)
{
    if ($user->subscription_status === 'Pending') {
        if (! $user->plan_id) {
            return redirect()->route('onboarding.select-plan');
        }
        if (! $user->websites()->exists()) {
            return redirect()->route('onboarding.subdomain');
        }
        return redirect()->route('onboarding.paywall');
    }

    if ($user->subscription_status === 'Expired') {
        // Jika kamu tidak punya route bernama 'expired', buat view-nya atau arahkan ke dashboard
        return redirect()->route('dashboard'); 
    }

    // Pastikan rute 'dashboard' ada di web.php
    return redirect()->intended(route('dashboard'));
}
}
