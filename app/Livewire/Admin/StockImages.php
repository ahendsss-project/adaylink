<?php

namespace App\Livewire\Admin;

use App\Models\StockImage;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;

class StockImages extends Component
{
    public string $filterCategory = '';

    public function delete(int $imageId): void
    {
        $image = StockImage::findOrFail($imageId);

        // Delete file from storage
        upload_delete($image->image_url);

        $image->delete();
        session()->flash('success', 'Gambar berhasil dihapus.');
        $this->dispatch('$refresh');
    }

    public function render()
    {
        $query = StockImage::orderBy('created_at', 'desc');

        if ($this->filterCategory) {
            $query->where('category', $this->filterCategory);
        }

        $images = $query->get();
        $categories = ['Tour', 'Vehicle', 'HeroBanner', 'General'];

        return view('livewire.admin.stock-images', compact('images', 'categories'))
            ->layout('components.layouts.admin')
            ->title('Stock Images - Admin adaylink');
    }
}
