<?php

namespace App\Livewire\Onboarding;

use App\Models\Website;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Validate;
use Livewire\Component;

class SubdomainClaim extends Component
{
    #[Validate('required|string|max:255')]
    public string $site_name = '';

    #[Validate('nullable|string|max:5|in:id,en')]
    public string $default_locale = 'id';

    #[Validate('required|string|min:3|max:50|regex:/^[a-z0-9][a-z0-9\-]*[a-z0-9]$/|unique:websites,subdomain')]
    public string $subdomain = '';

    public function updatedSubdomain(string $value): void
    {
        // Auto-format: lowercase, replace spaces with hyphens, remove special chars
        $this->subdomain = strtolower(preg_replace('/[^a-z0-9\-]/', '', str_replace(' ', '-', $value)));
    }

    public function claim(): void
    {
        $this->validate();

        $user = Auth::guard('web')->user();

        // Check if user already has a website
        if ($user->websites()->exists()) {
            session()->flash('error', 'Anda sudah memiliki website terdaftar.');

            return;
        }

        // Create website with is_active = false
        Website::create([
            'user_id' => $user->id,
            'site_name' => $this->site_name,
            'subdomain' => $this->subdomain,
            'default_locale' => $this->default_locale,
            'is_active' => false,
        ]);

        // Redirect to paywall
        $this->redirect(route('onboarding.paywall'), navigate: true);
    }

    public function render()
    {
        return view('livewire.onboarding.subdomain-claim')
            ->layout('components.layouts.onboarding');
    }
}
