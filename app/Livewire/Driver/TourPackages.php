<?php

namespace App\Livewire\Driver;

use App\Models\TourPackage;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class TourPackages extends Component
{
    public function delete(string $tourId): void
    {
        $website = Auth::guard('web')->user()->websites->first();

        $tour = TourPackage::where('id', $tourId)
            ->where('website_id', $website->id)
            ->firstOrFail();

        $tour->delete();

        session()->flash('success', 'Paket tour berhasil dihapus.');

        $this->dispatch('$refresh');
    }

    public function render()
    {
        $user = Auth::guard('web')->user();
        $website = $user->websites->first();
        $plan = $user->plan;

        $tours = $website
            ? $website->tourPackages()->orderBy('created_at', 'desc')->get()
            : collect();

        $maxTours = $plan ? $plan->max_tours : 0;
        $currentCount = $tours->count();
        $quotaExceeded = $currentCount >= $maxTours;

        return view('livewire.driver.tour-packages', compact('tours', 'maxTours', 'currentCount', 'quotaExceeded'))
            ->layout('components.layouts.driver')
            ->title('Paket Tour - adaylink');
    }
}
