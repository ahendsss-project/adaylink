<?php

namespace App\Livewire\Onboarding;

use App\Models\SubscriptionPlan;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class SelectPlan extends Component
{
    public function selectPlan(int $planId): void
    {
        $plan = SubscriptionPlan::where('id', $planId)
            ->where('is_active', true)
            ->firstOrFail();

        $user = Auth::guard('web')->user();

        $user->update([
            'plan_id' => $plan->id,
            'subscription_plan' => $plan->name,
        ]);

        $this->redirect(route('onboarding.subdomain'), navigate: true);
    }

    public function render()
    {
        $plans = SubscriptionPlan::where('is_active', true)
            ->orderBy('price', 'asc')
            ->get();

        return view('livewire.onboarding.select-plan', compact('plans'))
            ->layout('components.layouts.onboarding')
            ->title('Pilih Paket - adaylink');
    }
}
