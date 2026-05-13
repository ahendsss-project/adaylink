<?php

namespace App\Livewire\Admin;

use App\Models\StockImage;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithFileUploads;

class StockImageForm extends Component
{
    use WithFileUploads;

    public ?StockImage $stockImage = null;

    #[Validate('required|in:Tour,Vehicle,HeroBanner,General')]
    public string $category = 'General';

    #[Validate('required|string|max:255')]
    public string $title = '';

    #[Validate('required|string|max:255')]
    public string $alt_text = '';

    #[Validate('required|file|mimes:jpg,jpeg,png,webp|max:1024|dimensions:min_width=100,min_height=100')]
    public $image = null;

    public bool $isEditing = false;

    public function mount(?int $imageId = null): void
    {
        if ($imageId) {
            $this->stockImage = StockImage::findOrFail($imageId);
            $this->fill([
                'category' => $this->stockImage->category,
                'title' => $this->stockImage->title,
                'alt_text' => $this->stockImage->alt_text,
            ]);
            $this->isEditing = true;
        }
    }

    public function updatedImage(): void
    {
        $this->validateOnly('image');
    }

    public function save(): void
    {
        if ($this->isEditing) {
            $this->validateOnly('category');
            $this->validateOnly('title');
            $this->validateOnly('alt_text');
        } else {
            $this->validate();
        }

        $imageUrl = $this->stockImage?->image_url;

        if ($this->image) {
            // Delete old image if replacing
            upload_delete($imageUrl);
            $imageUrl = upload_store('stock-images', $this->image);
        }

        $data = [
            'category' => $this->category,
            'title' => $this->title,
            'alt_text' => $this->alt_text,
            'image_url' => $imageUrl,
        ];

        if ($this->isEditing) {
            $this->stockImage->update($data);
            session()->flash('success', 'Gambar berhasil diperbarui!');
        } else {
            StockImage::create($data);
            session()->flash('success', 'Gambar berhasil ditambahkan!');
        }

        $this->redirect(route('admin.stock-images.index'), navigate: true);
    }

    public function render()
    {
        return view('livewire.admin.stock-image-form')
            ->layout('components.layouts.admin')
            ->title(($this->isEditing ? 'Edit' : 'Upload') . ' Stock Image - Admin adaylink');
    }
}
