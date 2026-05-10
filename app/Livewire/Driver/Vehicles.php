<?php

namespace App\Livewire\Driver;

use App\Models\Vehicle;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Vehicles extends Component
{
    public function delete(string $vehicleId): void
    {
        $website = Auth::guard('web')->user()->websites->first();

        $vehicle = Vehicle::where('id', $vehicleId)
            ->where('website_id', $website->id)
            ->firstOrFail();

        $vehicle->delete();

        session()->flash('success', 'Armada berhasil dihapus.');

        $this->dispatch('$refresh');
    }

    public function render()
    {
        $user = Auth::guard('web')->user();
        $website = $user->websites->first();
        $plan = $user->plan;

        $vehicles = $website
            ? $website->vehicles()->orderBy('created_at', 'desc')->get()
            : collect();

        $maxVehicles = $plan ? $plan->max_vehicles : 0;
        $currentCount = $vehicles->count();
        $quotaExceeded = $currentCount >= $maxVehicles;

        return view('livewire.driver.vehicles', compact('vehicles', 'maxVehicles', 'currentCount', 'quotaExceeded'))
            ->layout('components.layouts.driver')
            ->title('Armada - adaylink');
    }
}
