<?php

namespace App\Livewire\Driver;

use App\Models\StockImage;
use App\Models\TourPackage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Attributes\Validate;
use Livewire\Component;

class TourPackageForm extends Component
{
    public ?TourPackage $tourPackage = null;

    #[Validate('required|string|max:255')]
    public string $title = '';

    #[Validate('required|string|max:255|unique:tour_packages,slug')]
    public string $slug = '';

    #[Validate('nullable|numeric|min:0')]
    public string $price_start_from = '';

    #[Validate('nullable|string|max:255')]
    public string $duration_text = '';

    #[Validate('nullable|string')]
    public string $description = '';

    #[Validate('nullable|string|max:255')]
    public string $thumbnail_url = '';

    /** @var array<int, string> */
    public array $itinerary_items = [];

    /** @var array<int, string> */
    public array $include_items = [];

    /** @var array<int, string> */
    public array $exclude_items = [];

    #[Validate('nullable|string')]
    public string $notes = '';

    #[Validate('boolean')]
    public bool $is_featured = false;

    public bool $isEditing = false;

    public bool $quotaExceeded = false;

    public int $currentCount = 0;

    public int $maxTours = 0;

    public function mount(?string $tourId = null): void
    {
        $user = Auth::guard('web')->user();
        $website = $user->websites->first();
        $plan = $user->plan;

        $this->maxTours = $plan ? $plan->max_tours : 0;
        $this->currentCount = $website ? $website->tourPackages()->count() : 0;

        if ($tourId) {
            $this->tourPackage = TourPackage::where('id', $tourId)
                ->where('website_id', $website->id)
                ->firstOrFail();

            $this->fill([
                'title' => $this->tourPackage->title,
                'slug' => $this->tourPackage->slug,
                'price_start_from' => (string) $this->tourPackage->price_start_from,
                'duration_text' => $this->tourPackage->duration_text ?? '',
                'description' => $this->tourPackage->description ?? '',
                'thumbnail_url' => $this->tourPackage->thumbnail_url ?? '',
                'notes' => $this->tourPackage->notes ?? '',
                'is_featured' => $this->tourPackage->is_featured,
            ]);

            // Load existing array fields
            $this->itinerary_items = $this->tourPackage->itinerary ?? [''];
            $this->include_items = $this->tourPackage->includes ?? [''];
            $this->exclude_items = $this->tourPackage->excludes ?? [''];

            // Ensure at least one item
            if (empty($this->itinerary_items)) $this->itinerary_items = [''];
            if (empty($this->include_items)) $this->include_items = [''];
            if (empty($this->exclude_items)) $this->exclude_items = [''];

            $this->isEditing = true;
        } else {
            // Check quota for new tour
            if ($this->currentCount >= $this->maxTours) {
                $this->quotaExceeded = true;
            }
            // Start with one empty item each
            $this->itinerary_items = [''];
            $this->include_items = [''];
            $this->exclude_items = [''];
        }
    }

    public function updatedTitle(string $value): void
    {
        if (! $this->isEditing) {
            $this->slug = Str::slug($value);
        }
    }

    public function selectStockImage(string $url): void
    {
        $this->thumbnail_url = upload_url($url);
    }

    // Itinerary management
    public function addItineraryDay(): void
    {
        $this->itinerary_items[] = '';
    }

    public function removeItineraryDay(int $index): void
    {
        if (isset($this->itinerary_items[$index]) && count($this->itinerary_items) > 1) {
            unset($this->itinerary_items[$index]);
            $this->itinerary_items = array_values($this->itinerary_items);
        }
    }

    // Include management
    public function addIncludeItem(): void
    {
        $this->include_items[] = '';
    }

    public function removeIncludeItem(int $index): void
    {
        if (isset($this->include_items[$index]) && count($this->include_items) > 1) {
            unset($this->include_items[$index]);
            $this->include_items = array_values($this->include_items);
        }
    }

    // Exclude management
    public function addExcludeItem(): void
    {
        $this->exclude_items[] = '';
    }

    public function removeExcludeItem(int $index): void
    {
        if (isset($this->exclude_items[$index]) && count($this->exclude_items) > 1) {
            unset($this->exclude_items[$index]);
            $this->exclude_items = array_values($this->exclude_items);
        }
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:tour_packages,slug',
            'price_start_from' => 'nullable|numeric|min:0',
            'duration_text' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'thumbnail_url' => 'nullable|string|max:255|url',
            'itinerary_items' => 'nullable|array',
            'itinerary_items.*' => 'nullable|string',
            'include_items' => 'nullable|array',
            'include_items.*' => 'nullable|string',
            'exclude_items' => 'nullable|array',
            'exclude_items.*' => 'nullable|string',
            'notes' => 'nullable|string',
            'is_featured' => 'boolean',
        ];
    }

    public function save(): void
    {
        if (! $this->isEditing && $this->quotaExceeded) {
            session()->flash('error', 'Limit paket tercapai. Silakan upgrade paket Anda untuk menambah paket tour.');

            return;
        }

        $rules = $this->rules();

        if ($this->isEditing) {
            $rules['slug'] = 'required|string|max:255|unique:tour_packages,slug,' . $this->tourPackage->id;
        }

        $this->validate($rules);

        $website = Auth::guard('web')->user()->websites->first();

        // Filter empty items
        $itinerary = array_values(array_filter($this->itinerary_items, fn ($item) => ! empty(trim($item))));
        $includes = array_values(array_filter($this->include_items, fn ($item) => ! empty(trim($item))));
        $excludes = array_values(array_filter($this->exclude_items, fn ($item) => ! empty(trim($item))));

        $data = [
            'website_id' => $website->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'price_start_from' => $this->price_start_from ? (float) $this->price_start_from : 0,
            'duration_text' => $this->duration_text ?: null,
            'description' => $this->description ?: null,
            'thumbnail_url' => $this->thumbnail_url ?: null,
            'itinerary' => ! empty($itinerary) ? $itinerary : null,
            'includes' => ! empty($includes) ? $includes : null,
            'excludes' => ! empty($excludes) ? $excludes : null,
            'notes' => $this->notes ?: null,
            'is_featured' => $this->is_featured,
        ];

        if ($this->isEditing) {
            $this->tourPackage->update($data);
            session()->flash('success', 'Paket tour berhasil diperbarui!');
        } else {
            TourPackage::create($data);
            session()->flash('success', 'Paket tour berhasil ditambahkan!');
        }

        $this->redirect(route('driver.tours.index'), navigate: true);
    }

    public function render()
    {
        $stockImages = StockImage::where('category', 'Tour')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('livewire.driver.tour-package-form', compact('stockImages'))
            ->layout('components.layouts.driver')
            ->title($this->isEditing ? 'Edit Paket Tour' : 'Tambah Paket Tour' . ' - adaylink');
    }
}
