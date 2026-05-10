<?php

namespace App\Livewire\Driver;

use App\Models\Vehicle;
use App\Models\TourPackage;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Dashboard extends Component
{
    public function render()
    {
        $user = Auth::guard('web')->user();
        $website = $user->websites->first();
        $vehicleCount = $website ? $website->vehicles()->count() : 0;
        $tourCount = $website ? $website->tourPackages()->count() : 0;

        // Plan quota info
        $plan = $user->plan;
        $maxVehicles = $plan?->max_vehicles ?? 0;
        $maxTours = $plan?->max_tours ?? 0;

        // Subscription days remaining
        $daysLeft = $user->subscription_expires_at
            ? now()->diffInDays($user->subscription_expires_at, false)
            : 0;

        // Monthly content activity (last 6 months)
        $monthlyActivity = [];
        $monthLabels = [];
        if ($website) {
            for ($i = 5; $i >= 0; $i--) {
                $date = now()->subMonths($i);
                $monthLabels[] = $date->format('M');
                $monthlyActivity[] = Vehicle::where('website_id', $website->id)
                    ->whereYear('created_at', $date->year)
                    ->whereMonth('created_at', $date->month)
                    ->count()
                    + TourPackage::where('website_id', $website->id)
                    ->whereYear('created_at', $date->year)
                    ->whereMonth('created_at', $date->month)
                    ->count();
            }
        }

        // Content distribution
        $contentLabels = ['Armada', 'Paket Tour'];
        $contentCounts = [$vehicleCount, $tourCount];

        return view('livewire.driver.dashboard', compact(
            'user', 'website', 'vehicleCount', 'tourCount',
            'plan', 'maxVehicles', 'maxTours', 'daysLeft',
            'monthLabels', 'monthlyActivity',
            'contentLabels', 'contentCounts',
        ))
            ->layout('components.layouts.driver')
            ->title('Dashboard - adaylink');
    }
}
