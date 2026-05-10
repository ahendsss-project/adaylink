<?php

namespace App\Livewire\Admin;

use App\Models\SubscriptionPlan;
use Livewire\Component;

class SubscriptionPlans extends Component
{
    public function delete(int $planId): void
    {
        $plan = SubscriptionPlan::findOrFail($planId);

        // Prevent deleting if users are subscribed
        if ($plan->users()->exists()) {
            session()->flash('error', "Paket '{$plan->name}' tidak bisa dihapus karena ada user yang berlangganan.");

            return;
        }

        $plan->delete();
        session()->flash('success', "Paket '{$plan->name}' berhasil dihapus.");
        $this->dispatch('$refresh');
    }

    public function toggleActive(int $planId): void
    {
        $plan = SubscriptionPlan::findOrFail($planId);
        $plan->update(['is_active' => ! $plan->is_active]);
        session()->flash('success', "Paket '{$plan->name}' " . ($plan->is_active ? 'diaktifkan' : 'dinonaktifkan') . '.');
        $this->dispatch('$refresh');
    }

    public function render()
    {
        $plans = SubscriptionPlan::orderBy('price', 'asc')->get();

        return view('livewire.admin.subscription-plans', compact('plans'))
            ->layout('components.layouts.admin')
            ->title('Subscription Plans - Admin adaylink');
    }
}
