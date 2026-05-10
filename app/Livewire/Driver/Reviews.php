<?php

namespace App\Livewire\Driver;

use App\Models\Review;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.driver')]
class Reviews extends Component
{
    use WithPagination;

    public string $filter = 'all'; // all, pending, approved
    public string $search = '';

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingFilter(): void
    {
        $this->resetPage();
    }

    public function approve(string $reviewId): void
    {
        $review = $this->findReview($reviewId);
        if ($review) {
            $review->update(['is_approved' => true]);
            session()->flash('success', 'Review berhasil disetujui.');
        }
    }

    public function unapprove(string $reviewId): void
    {
        $review = $this->findReview($reviewId);
        if ($review) {
            $review->update(['is_approved' => false]);
            session()->flash('success', 'Persetujuan review dibatalkan.');
        }
    }

    public function delete(string $reviewId): void
    {
        $review = $this->findReview($reviewId);
        if ($review) {
            $review->delete();
            session()->flash('success', 'Review berhasil dihapus.');
        }
    }

    private function findReview(string $reviewId): ?Review
    {
        $user = Auth::guard('web')->user();
        $websiteIds = $user->websites()->pluck('id');

        return Review::where('id', $reviewId)
            ->whereIn('website_id', $websiteIds)
            ->first();
    }

    public function render()
    {
        $user = Auth::guard('web')->user();
        $websiteIds = $user->websites()->pluck('id');

        $query = Review::whereIn('website_id', $websiteIds)
            ->when($this->search, function ($q) {
                $q->where('reviewer_name', 'like', "%{$this->search}%")
                    ->orWhere('comment', 'like', "%{$this->search}%");
            });

        match ($this->filter) {
            'pending' => $query->where('is_approved', false),
            'approved' => $query->where('is_approved', true),
            default => null,
        };

        $reviews = $query->orderBy('created_at', 'desc')->paginate(10);

        // Stats
        $totalReviews = Review::whereIn('website_id', $websiteIds)->count();
        $pendingCount = Review::whereIn('website_id', $websiteIds)->where('is_approved', false)->count();
        $approvedCount = Review::whereIn('website_id', $websiteIds)->where('is_approved', true)->count();
        $avgRating = Review::whereIn('website_id', $websiteIds)->where('is_approved', true)->avg('rating');

        return view('livewire.driver.reviews', compact(
            'reviews',
            'totalReviews',
            'pendingCount',
            'approvedCount',
            'avgRating',
        ));
    }
}
