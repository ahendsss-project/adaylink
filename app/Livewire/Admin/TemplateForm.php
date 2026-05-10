<?php

namespace App\Livewire\Admin;

use App\Models\Template;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithFileUploads;

class TemplateForm extends Component
{
    use WithFileUploads;

    public ?Template $template = null;

    #[Validate('required|string|max:255')]
    public string $name = '';

    #[Validate('required|string|max:255')]
    public string $folder_name = '';

    #[Validate('nullable|string|max:255')]
    public string $thumbnail_url = '';

    #[Validate('required|in:Basic,Premium')]
    public string $tier = 'Basic';

    #[Validate('boolean')]
    public bool $is_active = true;

    public $thumbnail_image = null;

    public bool $isEditing = false;

    public function mount(?int $templateId = null): void
    {
        if ($templateId) {
            $this->template = Template::findOrFail($templateId);
            $this->fill([
                'name' => $this->template->name,
                'folder_name' => $this->template->folder_name ?? '',
                'thumbnail_url' => $this->template->thumbnail_url ?? '',
                'tier' => $this->template->tier,
                'is_active' => $this->template->is_active,
            ]);
            $this->isEditing = true;
        }
    }

    public function updatedThumbnailImage(): void
    {
        $this->validateOnly('thumbnail_image');
    }

    public function save(): void
    {
        $this->validate();

        // Handle thumbnail upload
        if ($this->thumbnail_image) {
            upload_delete($this->thumbnail_url);
            $this->thumbnail_url = upload_store('templates', $this->thumbnail_image);
        }

        $data = [
            'name' => $this->name,
            'folder_name' => $this->folder_name,
            'thumbnail_url' => $this->thumbnail_url,
            'tier' => $this->tier,
            'is_active' => $this->is_active,
        ];

        if ($this->isEditing) {
            $this->template->update($data);
            session()->flash('success', 'Template berhasil diperbarui!');
        } else {
            Template::create($data);
            session()->flash('success', 'Template baru berhasil ditambahkan!');
        }

        $this->redirect(route('admin.templates.index'), navigate: true);
    }

    public function render()
    {
        return view('livewire.admin.template-form')
            ->layout('components.layouts.admin')
            ->title(($this->isEditing ? 'Edit' : 'Tambah') . ' Template - Admin adaylink');
    }
}
