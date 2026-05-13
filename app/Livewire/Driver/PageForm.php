<?php

namespace App\Livewire\Driver;

use App\Models\Page;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Livewire\Attributes\Validate;
use Livewire\Component;

class PageForm extends Component
{
    public ?Page $page = null;

    #[Validate('required|string|max:255')]
    public string $title = '';

    #[Validate('required|string|max:255')]
    public string $slug = '';

    #[Validate('nullable|string')]
    public string $content = '';

    #[Validate('boolean')]
    public bool $is_published = false;

    #[Validate('nullable|integer|min:0')]
    public string $sort_order = '0';

    public bool $isEditing = false;

    public bool $quotaExceeded = false;

    public int $currentCount = 0;

    public int $maxPages = 0;

    // Translation support
    public bool $multilanguageEnabled = false;
    public string $secondaryLocale = 'en';

    /** @var array<string, string> Translation fields for secondary locale */
    public array $tr = [];

    public function mount(?string $pageId = null): void
    {
        $user = Auth::guard('web')->user();
        $website = $user->websites->first();
        $plan = $user->plan;

        $this->maxPages = $plan ? $plan->max_pages : 0;
        $this->currentCount = $website ? Page::where('website_id', $website->id)->count() : 0;

        // Determine multilanguage availability
        $this->multilanguageEnabled = $plan ? $plan->hasFeature('multilanguage') : false;
        $defaultLocale = $website->default_locale ?? 'id';
        $this->secondaryLocale = $defaultLocale === 'id' ? 'en' : 'id';

        if ($pageId) {
            $this->page = Page::where('id', $pageId)
                ->where('website_id', $website->id)
                ->firstOrFail();

            $this->fill([
                'title' => $this->page->title,
                'slug' => $this->page->slug,
                'content' => $this->page->content ?? '',
                'is_published' => $this->page->is_published,
                'sort_order' => (string) ($this->page->sort_order ?? 0),
            ]);

            // Load translations for secondary locale
            $this->tr = [
                'title' => $this->page->getTranslation('title', $this->secondaryLocale) ?? '',
                'content' => $this->page->getTranslation('content', $this->secondaryLocale) ?? '',
            ];

            $this->isEditing = true;
        } else {
            // Check quota for new page
            if ($this->currentCount >= $this->maxPages) {
                $this->quotaExceeded = true;
            }

            // Initialize empty translation fields
            $this->tr = ['title' => '', 'content' => ''];
        }
    }

    public function updatedTitle(string $value): void
    {
        if (! $this->isEditing) {
            $this->slug = Str::slug($value);
        }
    }

    public function save(): void
    {
        // Block if quota exceeded (only for new)
        if (! $this->isEditing && $this->quotaExceeded) {
            session()->flash('error', 'Limit halaman tercapai. Silakan upgrade paket Anda untuk menambah halaman.');

            return;
        }

        $this->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255',
            'content' => 'nullable|string',
            'is_published' => 'boolean',
            'sort_order' => 'nullable|integer|min:0',
            'tr.title' => 'nullable|string|max:255',
            'tr.content' => 'nullable|string',
        ]);

        $website = Auth::guard('web')->user()->websites->first();

        // Ensure slug is unique within this website
        $slugQuery = Page::where('website_id', $website->id)->where('slug', $this->slug);
        if ($this->isEditing) {
            $slugQuery->where('id', '!=', $this->page->id);
        }

        if ($slugQuery->exists()) {
            $this->addError('slug', 'Slug sudah digunakan oleh halaman lain.');
            return;
        }

        $data = [
            'website_id' => $website->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'content' => $this->content ?: null,
            'is_published' => $this->is_published,
            'sort_order' => (int) $this->sort_order,
        ];

        // Build translations JSON
        if ($this->multilanguageEnabled) {
            $existingTranslations = $this->isEditing ? ($this->page->translations ?? []) : [];

            $hasTranslation = !empty(trim($this->tr['title'] ?? ''))
                || !empty(trim($this->tr['content'] ?? ''));

            if ($hasTranslation) {
                $existingTranslations[$this->secondaryLocale] = array_filter([
                    'title' => $this->tr['title'] ?: null,
                    'content' => $this->tr['content'] ?: null,
                ], fn($v) => $v !== null);
            } else {
                unset($existingTranslations[$this->secondaryLocale]);
            }

            $data['translations'] = !empty($existingTranslations) ? $existingTranslations : null;
        }

        if ($this->isEditing) {
            $this->page->update($data);
            session()->flash('success', 'Halaman berhasil diperbarui!');
        } else {
            Page::create($data);
            session()->flash('success', 'Halaman berhasil ditambahkan!');
        }

        $this->redirect(route('driver.pages.index'), navigate: true);
    }

    public function render()
    {
        return view('livewire.driver.page-form')
            ->layout('components.layouts.driver')
            ->title($this->isEditing ? 'Edit Halaman - adaylink' : 'Tambah Halaman - adaylink');
    }
}
