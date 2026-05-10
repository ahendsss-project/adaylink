<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    /**
     * Store a new review from a public visitor.
     */
    public function store(Request $request)
    {
        $website = $request->attributes->get('website');

        $validated = $request->validate([
            'reviewer_name' => 'required|string|max:100',
            'reviewer_email' => 'nullable|email|max:255',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|max:1000',
        ]);

        Review::create([
            'website_id' => $website->id,
            'reviewer_name' => $validated['reviewer_name'],
            'reviewer_email' => $validated['reviewer_email'] ?? null,
            'rating' => $validated['rating'],
            'comment' => $validated['comment'],
            'is_approved' => false, // Requires driver approval
        ]);

        return redirect()->back()->with('review_success', 'Terima kasih! Review Anda telah dikirim dan menunggu persetujuan.');
    }
}
