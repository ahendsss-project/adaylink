<?php

namespace App\Http\Controllers;

use App\Models\SubscriptionPlan;

class PricingController extends Controller
{
    /**
     * Show the public pricing page.
     */
    public function index()
    {
        $plans = SubscriptionPlan::where('is_active', true)
            ->orderBy('price', 'asc')
            ->get();

        return view('public.pricing', compact('plans'));
    }
}
