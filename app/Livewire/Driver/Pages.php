<?php

namespace App\Livewire\Driver;

use App\Models\Page;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class Pages extends Component
{
    use WithPagination;

    protected $paginationTheme = 'tailwind';

    #[Url(as: 'q')]
    public string $search = '';

    public function togglePublish(string $pageId): void
    {
        $website = Auth::guard('web')->user()->websites->first();

        $page = Page::where('id', $pageId)
            ->where('website_id', $website->id)
            ->firstOrFail();

        $page->update(['is_published' => ! $page->is_published]);

        session()->flash('success', $page->is_published ? 'Halaman berhasil dipublikasikan.' : 'Halaman berhasil disembunyikan.');

        $this->dispatch('$refresh');
    }

    public function delete(string $pageId): void
    {
        $website = Auth::guard('web')->user()->websites->first();

        $page = Page::where('id', $pageId)
            ->where('website_id', $website->id)
            ->firstOrFail();

        $page->delete();

        session()->flash('success', 'Halaman berhasil dihapus.');

        $this->dispatch('$refresh');
    }

    public function render()
    {
        $user = Auth::guard('web')->user();
        $website = $user->websites->first();
        $plan = $user->plan;

        $query = Page::where('website_id', $website->id)
            ->orderBy('sort_order')
            ->orderBy('created_at', 'desc');

        if ($this->search) {
            $query->where('title', 'like', '%' . $this->search . '%');
        }

        $pages = $query->paginate(10);

        $maxPages = $plan ? $plan->max_pages : 0;
        $currentCount = Page::where('website_id', $website->id)->count();
        $quotaExceeded = $currentCount >= $maxPages;

        return view('livewire.driver.pages', compact('pages', 'maxPages', 'currentCount', 'quotaExceeded'))
            ->layout('components.layouts.driver')
            ->title('Halaman - adaylink');
    }
}
