<?php

namespace App\Livewire\Driver;

use App\Models\StockImage;
use App\Models\Vehicle;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Validate;
use Livewire\Component;

class VehicleForm extends Component
{
    public ?Vehicle $vehicle = null;

    #[Validate('required|string|max:255')]
    public string $model_name = '';

    #[Validate('required|integer|min:1')]
    public string $capacity_people = '';

    #[Validate('required|numeric|min:0')]
    public string $price_per_day = '';

    #[Validate('nullable|string|max:255')]
    public string $image_url = '';

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

    public function selectStockImage(string $url): void
    {
        $this->image_url = upload_url($url);
    }

    public function save(): void
    {
        // Block if quota exceeded (only for new)
        if (! $this->isEditing && $this->quotaExceeded) {
            session()->flash('error', 'Limit paket tercapai. Silakan upgrade paket Anda untuk menambah armada.');

            return;
        }

        $this->validate();

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
