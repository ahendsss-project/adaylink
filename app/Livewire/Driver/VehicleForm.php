<?php

namespace App\Livewire\Driver;

use App\Models\StockImage;
use App\Models\Vehicle;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithFileUploads;

class VehicleForm extends Component
{
    use WithFileUploads;
    public ?Vehicle $vehicle = null;

    #[Validate('required|string|max:255')]
    public string $model_name = '';

    #[Validate('required|integer|min:1')]
    public string $capacity_people = '';

    #[Validate('required|numeric|min:0')]
    public string $price_per_day = '';

    #[Validate('nullable|string|max:255')]
    public string $image_url = '';

    public $image_file = null;

    public bool $isEditing = false;

    public bool $quotaExceeded = false;

    public int $currentCount = 0;

    public int $maxVehicles = 0;

    public function mount(?string $vehicleId = null): void
    {
        $user = Auth::guard('web')->user();
        $website = $user->websites->first();
        $plan = $user->plan;

        $this->maxVehicles = $plan ? $plan->max_vehicles : 0;
        $this->currentCount = $website ? $website->vehicles()->count() : 0;

        if ($vehicleId) {
            $this->vehicle = Vehicle::where('id', $vehicleId)
                ->where('website_id', $website->id)
                ->firstOrFail();

            $this->fill([
                'model_name' => $this->vehicle->model_name,
                'capacity_people' => (string) $this->vehicle->capacity_people,
                'price_per_day' => (string) $this->vehicle->price_per_day,
                'image_url' => $this->vehicle->image_url ?? '',
            ]);

            $this->isEditing = true;
        } else {
            // Check quota for new vehicle
            if ($this->currentCount >= $this->maxVehicles) {
                $this->quotaExceeded = true;
            }
        }
    }

    public function updatedImageFile(): void
    {
        $this->validateOnly('image_file', [
            'image_file' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp', 'max:1024', 'dimensions:min_width=50,min_height=50'],
        ], [
            'image_file.mimes' => 'Format gambar harus WEBP, PNG, atau JPG.',
            'image_file.max' => 'Ukuran gambar maksimal 1 MB.',
            'image_file.dimensions' => 'Dimensi gambar minimal 50×50 piksel.',
        ]);
    }

    public function selectStockImage(string $url): void
    {
        $this->image_url = upload_url($url);
        $this->image_file = null;
    }

    public function save(): void
    {
        // Block if quota exceeded (only for new)
        if (! $this->isEditing && $this->quotaExceeded) {
            session()->flash('error', 'Limit paket tercapai. Silakan upgrade paket Anda untuk menambah armada.');

            return;
        }

        $this->validate();

        if ($this->image_file) {
            $this->validate([
                'image_file' => ['file', 'mimes:jpg,jpeg,png,webp', 'max:1024', 'dimensions:min_width=50,min_height=50'],
            ], [
                'image_file.mimes' => 'Format gambar harus WEBP, PNG, atau JPG.',
                'image_file.max' => 'Ukuran gambar maksimal 1 MB.',
                'image_file.dimensions' => 'Dimensi gambar minimal 50×50 piksel.',
            ]);
            $this->image_url = upload_url(upload_store('vehicles', $this->image_file));
            $this->image_file = null;
        }

        $website = Auth::guard('web')->user()->websites->first();

        $data = [
            'website_id' => $website->id,
            'model_name' => $this->model_name,
            'capacity_people' => (int) $this->capacity_people,
            'price_per_day' => (float) $this->price_per_day,
            'image_url' => $this->image_url ?: null,
        ];

        if ($this->isEditing) {
            $this->vehicle->update($data);
            session()->flash('success', 'Armada berhasil diperbarui!');
        } else {
            Vehicle::create($data);
            session()->flash('success', 'Armada berhasil ditambahkan!');
        }

        $this->redirect(route('driver.vehicles.index'), navigate: true);
    }

    public function render()
    {
        $stockImages = StockImage::where('category', 'Vehicle')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('livewire.driver.vehicle-form', compact('stockImages'))
            ->layout('components.layouts.driver')
            ->title($this->isEditing ? 'Edit Armada' : 'Tambah Armada' . ' - adaylink');
    }
}
